<?php

use App\Models\Store;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;

function setPDFPath($invoice)
{
    $data = [
        'invoice' => $invoice->load('details', 'payments', 'client', 'seller', 'contable'),
        'payment' => $invoice->payment
    ];
    $PDF = App::make('dompdf.wrapper');
    $pdf = $PDF->setOptions([
        'logOutputFile' => null,
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true
    ])->loadView('pages.invoices.letter', $data);
    //delete file if exists
    $name='files'.env('STORE_ID').'/invoices/invoice'.$invoice->id.date('ymdhi').'.pdf';
    Storage::disk('digitalocean')->put($name, $pdf->output(), 'public');
    $url= Storage::url($name);
    $path = $url;
    $pdf = [
        'note' => 'PDF Fact. Nº. ' . $invoice->number,
        'pathLetter' => $path,
    ];

    $invPDF = $invoice->pdf()->updateOrCreate(
        ['fileable_id' => $invoice->id],
        $pdf
    );
   
    $payment = $invoice->payments()->orderBy('id', 'desc')->first();
    $payment->pdf()->updateOrCreate(
        ['fileable_id' => $payment->id],
        $pdf
    );
}
function sendInvoiceWS($path, $phone, $number)
{
    $user=auth()->user();
    $phone='1'.preg_replace('/[^0-9]/', '', $phone);
    $phone2='1'.preg_replace('/[^0-9]/', '', $user->phone);
    $whatsapp_cloud_api = new WhatsAppCloudApi([
        'from_phone_number_id' => env('WHATSAPP_NUMBER_ID'),
        'access_token' => env('WHATSAPP_TOKEN'),
    ]);
    $document_name = basename($path);
    $document_caption =  $number;
    $user=auth()->user();
    $document_link = $path;
    $link_id = new LinkID($document_link);
    $whatsapp_cloud_api->sendDocument($phone, $link_id, $document_name, $document_caption);
    $whatsapp_cloud_api->sendDocument($phone2, $link_id, $document_name, $document_caption);
    $whatsapp_cloud_api->sendTextMessage($phone, 'Adjunto del documento Noº. '.$document_caption);
    $whatsapp_cloud_api->sendTextMessage($phone2, 'Adjunto del documento Noº. '.$document_caption);
}

function setIncome($model, $concepto, $amount)
{
    $store = auth()->user()->store;
    $place = auth()->user()->place;
    $income = $store->incomes()->create(
        [
            'amount' => $amount,
            'concepto' => $concepto,
            'place_id' => $place->id,
            'user_id' => $model->contable_id,
        ]
    );
    $model->incomes()->save($income);
}
function setPaymentTransaction($invoice, $payment, $client, $bank, $reference)
{
    $place = auth()->user()->place;
    $creditable =  $client->contable()->first();
    $ref = $invoice->comprobante ?: $invoice;
    $ref = $ref->number;
    $moneys = array($payment->efectivo, $payment->tarjeta, $payment->transferencia, $payment->rest);
    $max = array_search(max($moneys), $moneys);

    switch ($max) {
        case 0:
            setTransaction('Reg. abono Ref. Nº. ' . $ref, $ref, $moneys[$max], $place->cash(), $creditable, 'Cobrar Facturas');
            break;
        case 1:
            setTransaction('Reg. abono Ref. Nº. ' . $ref, $ref, $moneys[$max], $place->check(), $creditable, 'Cobrar Facturas');
            break;
        case 2:
            setTransaction('Reg. abono Ref. Nº. ' . $ref, $reference, $moneys[$max], $bank->contable()->first(), $creditable, 'Cobrar Facturas');
            break;
    }
    $moneys[$max] = 0;
    setTransaction('Reg. abono en Efectivo', $ref,  $moneys[0], $place->cash(), $creditable, 'Cobrar Facturas');
    setTransaction('Reg. vuelto de cambio', $ref,  $payment->cambio, $creditable, $place->cash(), 'Cobrar Facturas');
    setTransaction('Reg. abono por Cheque', $ref,  $moneys[1], $place->check(), $creditable, 'Cobrar Facturas');
    setTransaction('Reg. abono por Transferencia', $ref . ' | ' . $reference,  $moneys[2], optional($bank)->contable, $creditable, 'Cobrar Facturas');

    $client->update([
        'limit' => $client->limit + $payment->payed
    ]);
}
function amortizar($deuda, $interes, $cuotas)
    {
        $interes=$interes>0?$interes/100:0.000000001;
        $m = ($deuda * $interes * (pow((1 + $interes), ($cuotas)))) / ((pow((1 + $interes), ($cuotas))) - 1);
        $m = round($m, 2);
        $pagos = [];
        $saldo_inicial = $deuda;
        $suma = 0;
        for ($i = 0; $i < $cuotas; $i++) {
            $int = $saldo_inicial * ($interes / 100);
            $capital = $m - ($int * 100);
            $cuota = $m;
            $saldo_final = round($saldo_inicial - $capital, 2);
            $suma += $m;
            array_push(
                $pagos,
                [
                    'saldo' => round($saldo_inicial),
                    'interes' => round($int * 100),
                    'capital' => round($capital),
                    'debe' => round($cuota),
                    'restante' => round($saldo_final),
                ]
            );
            $saldo_inicial = $saldo_final;
        }
        $pagos = json_decode(json_encode($pagos));
        $suma = json_decode(json_encode($suma));
        $data = json_decode(json_encode(["pagos" => $pagos, "suma" => $suma]));
        return json_decode(json_encode($data));;
    }