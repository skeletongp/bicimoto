<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Classes\NumberColumn;
use App\Models\Cuota;
use App\Models\Place;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class CuotasVencidas extends LivewireDatatable
{
    public $padding = "px-2";
    public $headTitle = "Cuotas en estado de mora";
    public $client_id;
    public function builder()
    {
        $user = auth()->user();
        $this->client_id?$this->hideable=null:$this->hideable="select";
        $cuotas =
            Cuota::where('cuotas.fecha', '<', date('Y-m-d'))
            ->where('cuotas.status', '!=', 'pagado')
            ->orderBy('cuotas.fecha')
            ->leftjoin('clients', 'clients.id', '=', 'cuotas.client_id')
            ->leftjoin('invoices', 'invoices.id', '=', 'cuotas.invoice_id')
            ->leftJoin('contacts', 'contacts.id', '=', 'clients.contact_id')
            ->where('invoices.place_id', $user->place_id)
            ->where('invoices.deleted_at', null)
            ;
        Cache::put('cuotasvencidas' . env('STORE_ID'), $cuotas->count());
        $cuotas->when($this->client_id, function ($query) {
            return $query->where('clients.id', $this->client_id);
        });
        return $cuotas;
    }

    public function columns()
    {
        $place = optional(auth()->user())->place ?: Place::first();
        $moraRate = $place->preference->mora / 100;
        return [
            Column::checkbox(),
            DateColumn::name('fecha')->label('Fecha')->format('d-m-Y'),
            Column::callback(['invoices.id', 'invoices.number'], function ($id, $number) {
                $number = ltrim(substr($number, strpos($number, '-') + 1), '0');
                return "<a href=" . route('invoices.show', $id) . " class='hover:underline hover:text-blue-500'>" . str_pad($number, 3, "0", STR_PAD_LEFT) . "</a>";;
            })->label('Fact'),
            NumberColumn::name('saldo')->label('Saldo')->formatear('money'),
            NumberColumn::name('interes')->label('Interés')->formatear('money'),
            NumberColumn::name('capital')->label('Capital')->formatear('money'),
            Column::callback(['mora', 'debe'], function ($mora, $debe) use ($moraRate) {
                if (!$mora) {
                    return '$' . formatNumber($debe * $moraRate);
                }
                return '$' . formatNumber($mora);
            })->label('Mora')->contentAlignRight(),
            Column::callback(['debe', 'mora'], function ($debe, $mora) use ($moraRate) {
                if (!$mora) {
                    return '$' . formatNumber($debe * (1 + $moraRate));
                }
                return '$' . formatNumber($debe);
            })->label('Cuota')->contentAlignRight(),

            NumberColumn::name('restante')->label('Bal. Final')->formatear('money')->hide(),
            $this->client_id ?
                Column::callback('contacts.fullname', function ($client) {
                    return ellipsis($client, 30);
                })->label('Cliente')->searchable()->hide()
                :
                Column::callback('contacts.fullname', function ($client) {
                    return ellipsis($client, 30);
                })->label('Cliente')->searchable(),
            Column::name('contacts.cellphone')->label('Celular')->hide(),

        ];
    }
    public function buildActions()
    {
        return [

            Action::value('edit')->label('Cobrar cuotas')->callback(function ($mode, $items) {
                return redirect()->route('clients.paymany', ['cuotas' => implode(',', $items)]);
            }),
            Action::value('print')->label('imprimir')->callback(function ($mode, $items) {
                foreach($this->builder()->get() as $cuota){
                    
                        $place=optional(auth()->user())->place?:Place::first();
                        $mora = $place->preference->mora_rate/100;
                        if (
                            $cuota->fecha < Carbon::now()->subDays(5)->format('Y-m-d')
                            && $cuota->updated_at->format('Y-m-d H:i') ==  $cuota->created_at->format('Y-m-d H:i')
                        ) {
                            
                            $cuota->mora= $cuota->debe *$mora;
                            $cuota->debe = $cuota->debe * (1+$mora);
                            $cuota->save();
                            $invoice=$cuota->invoice->load('payments');
                            $invoice->update([
                                'rest'=>$invoice->rest+$cuota->mora,
                            ]);
                            $payment=$invoice->payments->last();
                            $payment->update([
                                'total'=>$payment->total+$cuota->mora,
                                'rest'=>$payment->rest+$cuota->mora,
                            ]);
                            $cuota->touch();
                        }
                return redirect()->route('vencidas');
            }
            }),

        ];
    }
}
