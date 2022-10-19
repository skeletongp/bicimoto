<?php

namespace App\Http\Livewire\Products;

use App\Models\Chasis;
use App\Models\Place;
use Mediconesystems\LivewireDatatables\Action;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Allchasis extends LivewireDatatable
{
    public $padding = 'px-2';
    public $hideable = 'select';
    public $headTitle = "Registro de chasis";
    public function builder()
    {
        $place = getPlace();
        $chasis = Chasis::
            where('chasis.place_id', $place->id)
            ->leftjoin('invoices', 'invoices.id', '=', 'chasis.invoice_id')
            ->leftJoin('clients', 'clients.id', '=', 'invoices.client_id')
            ->leftJoin('contacts', 'contacts.id', '=', 'clients.contact_id')
            ->orderBy('chasis.id', 'desc')
            ->groupBy('chasis.id')
            ;
        return $chasis;
    }

    public function columns()
    {
        return [
            Column::checkbox(),
            Column::callback(['invoices.id', 'invoices.number','chasis.code'], function ($id, $number, $code) {
                if ($id) {
                    return "<a href=" . route('invoices.show', $id) . " class='hover:underline text-blue-500 hover:text-blue-700'>" . ltrim(substr($number, strpos($number, '-') + 1), '0').'/'.$code . "</a>";;
                } else {
                    return $code;
                }
            })->label('Cod./Fact.'),
            Column::name('tipo')->label('Tipo')->hide()->editable(),
            Column::name('marca')->label('Marca')->hide()->editable(),
            Column::name('modelo')->label('Modelo')->hide()->editable(),
            Column::name('color')->label('Color')->editable(),
            Column::name('year')->label('Año'),
            Column::name('chasis')->label('Chasis'),
            Column::callback('contacts.fullname', function($fullname){
                return $fullname?:"Sin cliente";
            })->label('Cliente')->hide(),
            Column::callback('status', function ($status){
                return $status == 'Entregado' ? 'Vendido' : ($status == 'Pendiente' ? 'Disponible' : 'Cancelado');
            })->label('Status')->filterable([
                'Pendiente',
                'Entregado',
                'Cancelado',
            ]),

        ];
    }

    public function buildActions()
    {
        return [

            Action::value('print')->label('Impresión')->callback(function ($mode, $items) {
                if (count($items) > 250) {
                    $this->emit('showAlert','No se puede imprimir más de 250 registros','error');
                } else {
                    return redirect()->route('products.chasis', ['chasis' => $items]);
                }
                
            }),

            

           
        ];
    }
   
}
