<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Classes\NumberColumn;
use App\Http\Livewire\UniqueDateTrait;
use App\Models\Cuota;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class InvoiceCuota extends LivewireDatatable
{
    public $invoice_id;
    public $padding = "px-2";
    public $headTitle = "Cuotas de la factura";
    public function builder()
    {
        $cuotas = Cuota::where('cuotas.invoice_id', $this->invoice_id)
            ->join('invoices', 'cuotas.invoice_id', '=', 'invoices.id')
            ->join('clients', 'cuotas.client_id', '=', 'clients.id')
            ->orderBy('cuotas.fecha');
        $invoice = Invoice::find($this->invoice_id)->load('client.contact');
        $this->headTitle = "Amortización | " . ellipsis($invoice->client->contact->fullname, 30);

        return $cuotas;
    }

    public function columns()
    {
        return [
            Column::checkbox(),
            DateColumn::name('fecha')->label('Fecha')->format('d-m-Y')->filterable(),
            NumberColumn::name('saldo')->label('Saldo')->formatear('money'),
            NumberColumn::name('interes')->label('Interés')->formatear('money'),
            NumberColumn::name('capital')->label('Capital')->formatear('money'),
            NumberColumn::name('mora')->label('Mora')->formatear('money'),
            NumberColumn::name('debe')->label('Cuota')->formatear('money')->enableSummary(),
            Column::callback('status', function ($status) {
                if ($status == 'pendiente') {
                    return '<span class="text-red-400 font-semibold">Pendiente</span>';
                } else {
                    return '<span class="text-green-600 font-semibold">Pagada</span>';
                }
            })->label('Estado')->filterable([
                'pendiente' => 'Pendiente',
                'pagado' => 'Pagada',
            ]),
            DateColumn::name('payed_at')->label('F. Pago')->format('d-m-Y'),
        ];
    }
    public function buildActions()
    {
        return [

            Action::value('edit')->label('Cobrar cuotas')->callback(function ($mode, $items) {
                return redirect()->route('clients.paymany', ['cuotas' => implode(',', $items)]);
            }),
            Action::value('download')->label('Descargar Amortización')->callback(function ($mode, $items = [0]) {
                return redirect()->route('invoices.amortizacion', $this->invoice_id);
            }),
           /*  Action::value('print')->label('Imprimir')->callback(function ($mode, $items = [0]) {
                foreach ($items as $item) {
                    $cuota = Cuota::whereId($item)->first();
                    $payment = $cuota->payment;
                    if ($payment) {
                        $payment = $payment->load('payable.store', 'payer', 'payer.contact', 'place.preference', 'payable.payment', 'contable');
                        $invoice = $payment->payable;
                        $cuotas = $invoice->cuotas->whereNotNull('payed_at')->count();
                        $pendientes = $invoice->cuotas->whereNUll('payed_at')->count();
                        $cuotasTotal = $invoice->cuotas->count();
                        $proxima = $invoice->cuotas->whereNUll('payed_at')->first();
                        $payment->cuota = $cuota;
                        $payment->cuotas = $cuotas;
                        $proxima->fecha = Carbon::parse($proxima->fecha)->format('d/m/Y');
                        $payment->day = Carbon::parse($payment->day)->format('d/m/Y');
                        $payment->pendientes = $pendientes;
                        $payment->cuotasTotal = $cuotasTotal;
                        $payment->proxima = $proxima;
                        //$this->emit('printPayment', $payment);
                    }
                    continue;
                }
            }), */

        ];
    }

    public function summarize($column)
    {

        $results = json_decode(json_encode($this->results->items()), true);
        foreach ($results as $key => $value) {
            $val = json_decode(json_encode($value), true);
            $results[$key][$column] = preg_replace("/[^0-9 .]/", '', $val[$column]);
        }
        try {

            return "<h1 class='font-bold text-right'>" . '$' . formatNumber(array_sum(array_column($results, $column))) . "</h1>";;
        } catch (\TypeError $e) {
            return '';
        }
    }
}
