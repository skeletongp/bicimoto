<?php

namespace App\Http\Livewire\Clients;

use App\Http\Classes\NumberColumn;
use App\Models\Client;
use App\Models\Invoice;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ClientInvoice extends LivewireDatatable
{
    public $headTitle = "Historial de compras";
    public $client;
    public $padding = "px-2";
    public $total=0;
    public function builder()
    {
       if($this->total>0){
        $this->headTitle = "Pendiente de pago $".formatNumber($this->total);
       }
        $client=$this->client;
        $invoices =
        Invoice::where('invoices.client_id', $client->id)
        ->leftJoin('payments','payments.payable_id', '=', 'invoices.id' )
        ->where('payments.payable_type', 'App\Models\Invoice')
        ->leftJoin('clients', 'invoices.client_id', '=', 'clients.id')
        ->leftjoin('contacts', 'clients.contact_id', '=', 'contacts.id')
        ->leftjoin('cuotas', 'invoices.id', '=', 'cuotas.invoice_id')
        ->groupby('invoices.id')
        ;
        return $invoices;
    }
   
    public function columns()
    {
        return [
            Column::callback('invoices.id', function($id) {
                return view('components.view', ['url' => route('invoices.show', $id)]);
            })->label('Ver'),
            Column::callback('invoices.number', function($number) {
                return substr($number,strpos($number,'-')+1);
            })->label('Nro.'),
            
            DateColumn::name('invoices.created_at')->label('Hora')->format('d/m/Y h:i A'),
            Column::name('condition')->label('Condición')->filterable(['De Contado','1 A 15 Días', '16 A 30 Días']),
            NumberColumn::raw('payments.total AS monto')->label('Monto')->formatear('money', 'font-bold'),
            NumberColumn::raw('payments.total-invoices.rest AS Pagado')->label('Pagado')->formatear('money'),
            NumberColumn::raw('invoices.rest AS resta')->label('Resta')->formatear('money'),
         
        ];
    }

    public function buildActions()
    {
        return [

            Action::value('edit')->label('Cobrar facturas')->callback(function ($mode, $items) {
              return redirect()->route('clients.paymany', ['invoices'=>implode(',',$items)]);
            }),

        ];
    }
    public function updatedSelected(){
        $rest=Invoice::whereIn('id', $this->selected)->sum('rest');
        $this->total=$rest;
        $this->builder();
    }
}