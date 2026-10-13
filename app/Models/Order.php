<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'guest_email', 'confirmation_token', 'invoice_no', 'subtotal', 'shipping', 'discount', 'total',
        'payment_method', 'payment_id', 'payment_status', 'stock_restored', 'delivery_status',
        'coupon_id', 'address_id', 'notes',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->confirmation_token = $order->confirmation_token ?? Str::random(40);
        });

        static::updating(function (Order $order) {
            if (
                $order->isDirty('payment_status')
                && ! $order->stock_restored
                && in_array($order->payment_status, [
                    PaymentStatus::Failed->value,
                    PaymentStatus::Cancelled->value,
                ], true)
            ) {
                $order->restoreStock();
            }
        });
    }

    public function restoreStock(): void
    {
        foreach ($this->items as $item) {
            $model = $item->item_type === 'stationery' ? Stationery::class : Book::class;
            $product = $model::find($item->book_id);

            if (! $product || $product->pre_order) {
                continue;
            }

            $product->increment('stock', $item->qty);
        }

        $this->stock_restored = true;
    }

    public function confirmationUrl(): string
    {
        if ($this->user_id) {
            return route('order.confirmation', $this);
        }

        return route('order.confirmation', ['order' => $this, 'token' => $this->confirmation_token]);
    }

    public function paymentUrl(): string
    {
        if ($this->user_id) {
            return route('payment.process', $this);
        }

        return route('payment.process', ['order' => $this, 'token' => $this->confirmation_token]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
