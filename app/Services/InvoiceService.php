<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Response;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class InvoiceService
{
    public function generatePdf(Order $order): Mpdf
    {
        $order->load(['user', 'items.book', 'address', 'coupon']);

        $fontPath = resource_path('fonts');

        $defaultConfig   = (new ConfigVariables())->getDefaults();
        $fontDirs        = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData          = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'mode'              => 'utf-8',
            'format'            => 'A4',
            'margin_top'        => 25,
            'margin_bottom'     => 25,
            'margin_left'       => 20,
            'margin_right'      => 20,
            'fontDir'           => array_merge($fontDirs, [$fontPath]),
            'fontdata'          => $fontData + [
                'solaimanlipi' => [
                    'R'          => 'SolaimanLipi.ttf',
                    'useOTL'     => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'default_font'      => 'solaimanlipi',
            'autoScriptToLang'  => true,
            'autoLangToFont'    => true,
            'tempDir'           => storage_path('mpdf'),
        ]);

        $html = view('invoices.order', ['order' => $order])->render();
        $mpdf->WriteHTML($html);

        return $mpdf;
    }

    public function download(Order $order): Response
    {
        $mpdf = $this->generatePdf($order);

        return response($mpdf->Output("invoice-{$order->invoice_no}.pdf", 'S'), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="invoice-' . $order->invoice_no . '.pdf"',
        ]);
    }

    public function stream(Order $order): Response
    {
        $mpdf = $this->generatePdf($order);

        return response($mpdf->Output("invoice-{$order->invoice_no}.pdf", 'S'), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="invoice-' . $order->invoice_no . '.pdf"',
        ]);
    }
}
