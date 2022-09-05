<?php

namespace App\Http\Livewire\Reports;

use App\Http\Classes\NumberColumn;
use App\Http\Livewire\UniqueDateTrait;
use App\Models\Invoice;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class InvoicePorCobrar extends LivewireDatatable
{
    public $headTitle = "Facturas pendientes por cobrar";
    public $padding = "px-2";
    public $hideable = "select";
    use UniqueDateTrait;

    public function builder()
    {
        $place = auth()->user()->place;
        $invoices = Invoice::where('invoices.place_id',$place->id)->orderBy('invoices.created_at', 'desc')
            ->where('status', '=', 'cerrada')
            ->where('invoices.rest', '>', 0)
            ->leftjoin('clients', 'invoices.client_id', '=', 'clients.id')
            ->leftjoin('contacts','contacts.id','=','clients.contact_id')
            ->leftJoin('payments', 'payments.payable_id', '=', 'invoices.id')
            ->where('payments.payable_type', '=', 'App\Models\Invoice')
            ->groupBy('invoices.id');
        return $invoices;
    }

    public function columns()
    {
        return [
            Column::callback(['invoices.id','invoices.rest'], function($id,$rest) {
                if ($rest>0) {
                    return "  <a href=".route('invoices.show', [$id,'includeName'=>'showpayments','includeTitle'=>'Pagos']).
                    "><span class='fas w-8 text-center fa-hand-holding-usd'></span> </a>";
                } else {
                    return "  <a href=".route('invoices.show', $id)."><span class='fas w-8 text-center fa-eye'></span> </a>";
                }
            })->label(''),
            Column::callback(['number'], function ($number) {
                $number = ltrim(substr($number, strpos($number, '-') + 1), '0');
                return $number;
            })->label('Nro.')->searchable(),
            DateColumn::name('created_at')->label('Fecha')->searchable()->filterable(),  
            Column::callback(['contacts.fullname','name'], function ($client, $name)  {
               
                return ellipsis($name ?:$client, 20);
            })->label('Cliente')->searchable(),
            Column::name('condition')->label('Condición')->filterable([
                'De Contado', 'Contra Entrega', '1 A 15 Días', '16 A 30 Días', '31 A 45 Dïas'
            ]),
            NumberColumn::raw('(SUM(payments.payed)-SUM(payments.cambio))+invoices.rest AS monto')->label('Monto')->formatear('money'),
            NumberColumn::raw('SUM(payments.payed) AS pagado')->label('Pagado')->formatear('money')->hide(),
            NumberColumn::raw('SUM(payments.efectivo) AS efectivo')->label('Efectivo')->formatear('money')->hide(),
            NumberColumn::raw('SUM(payments.transferencia) AS transferencia')->label('Transf.')->formatear('money')->hide(),
            NumberColumn::raw('SUM(payments.tarjeta) AS tarjeta')->label('Otros')->formatear('money')->hide(),
            NumberColumn::raw('SUM(payments.cambio) AS cambio')->label('Cambio')->formatear('money')->hide(),
            NumberColumn::raw('invoices.rest AS rest')->label('Resta')->formatear('money','font-bold'),
         
           
        ];
    }
}