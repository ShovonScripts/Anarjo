<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\BkashCallbackRequest;
use App\Models\Order;
use App\Services\BkashService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class BkashController extends Controller
{
    public function __construct(
        protected BkashService $bkash
    ) {}

    public function initiate(Order $order): RedirectResponse
    {
        abort_unless(Gate::forUser(auth()->user())->allows('pay', $order), 403);

        if ($order->payment_status !== PaymentStatus::Pending->value) {
            return redirect()->to($order->confirmationUrl());
        }

        if (! $this->bkash->isConfigured()) {
            return redirect()->to($order->paymentUrl())
                ->with('error', 'bKash is not configured yet.');
        }

        try {
            $result = $this->bkash->createPayment($order);

            if (! empty($result['bkashURL'])) {
                return redirect()->away($result['bkashURL']);
            }

            return redirect()->to($order->paymentUrl())
                ->with('error', $result['message'] ?? 'Failed to initiate bKash payment.');
        } catch (\Exception $e) {
            return redirect()->to($order->paymentUrl())
                ->with('error', $e->getMessage());
        }
    }

    public function callback(BkashCallbackRequest $request): RedirectResponse
    {
        $paymentID = $request->input('paymentID');
        $status = $request->input('status');

        if (! $paymentID) {
            return redirect()->route('home')->with('error', 'Invalid payment response.');
        }

        // Look up order by the bKash paymentID we stored during createPayment.
        $order = Order::where('payment_id', $paymentID)->first();

        if (! $order) {
            return redirect()->route('home')->with('error', 'Order not found for this payment.');
        }

        try {
            if ($status === 'success') {
                $result = $this->bkash->executePayment($paymentID);

                if ($order->payment_status === PaymentStatus::Completed->value) {
                    return redirect()->to($order->confirmationUrl())
                        ->with('message', 'Payment successful!');
                }

                $transactionStatus = $result['transactionStatus'] ?? '';
                $amount = (float) ($result['amount'] ?? 0);

                if ($transactionStatus === 'Completed' && $amount === (float) $order->total) {
                    $order->update([
                        'payment_status' => PaymentStatus::Completed->value,
                        'payment_id' => $result['trxID'] ?? $paymentID,
                    ]);

                    return redirect()->to($order->confirmationUrl())
                        ->with('message', 'Payment successful!');
                }

                // Payment was attempted but didn't complete successfully.
                if ($order->payment_status === PaymentStatus::Pending->value) {
                    $order->update(['payment_status' => PaymentStatus::Failed->value]);
                }

                return redirect()->to($order->confirmationUrl())
                    ->with('error', 'Payment verification failed. Please try again.');
            }

            // status is not 'success' — user cancelled or something went wrong.
            if ($order->payment_status === PaymentStatus::Pending->value) {
                $order->update(['payment_status' => PaymentStatus::Cancelled->value]);
            }

            return redirect()->to($order->confirmationUrl())
                ->with('error', 'Payment was cancelled.');
        } catch (\Exception $e) {
            return redirect()->to($order->confirmationUrl())
                ->with('error', 'Payment error: '.$e->getMessage());
        }
    }
}
