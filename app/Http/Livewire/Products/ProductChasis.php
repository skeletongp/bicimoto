<?php

namespace App\Http\Livewire\Products;

use App\Models\Chasis;
use App\Models\Place;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ProductChasis extends LivewireDatatable
{
    public $product_id;
    public $padding = 'px-2';
    public $hideable = 'select';
    public $headTitle = "Registro de chasis";
    public function builder()
    {
        $place = getPlace();
        $chasis = Chasis::where('product_id', $this->product_id)
            ->where('chasis.place_id', $place->id)
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
            Column::callback(['invoices.id', 'invoices.number','chasis.code'], function ($id, $number, $code) {
                if ($id) {
                    return "<a href=" . route('invoices.show', $id) . " class='hover:underline text-blue-500 hover:text-blue-700'>" . ltrim(substr($number, strpos($number, '-') + 1), '0').'/'.$code . "</a>";;
                } else {
                    return $code;
                }
            })->label('Cod./Fact.'),
            Column::name('tipo')->label('Tipo')->searchable()->hide()->editable(),
            Column::name('marca')->label('Marca')->searchable()->hide()->editable(),
            Column::name('modelo')->label('Modelo')->searchable()->hide()->editable(),
            Column::name('color')->label('Color')->searchable()->editable(),
            Column::name('year')->label('Año')->searchable(),
            Column::name('chasis')->label('Chasis')->searchable(),
            Column::callback('contacts.fullname', function($fullname){
                return $fullname?:"Sin cliente";
            })->label('Cliente')->searchable()->hide(),
            Column::callback('status', function ($status){
                return $status == 'Entregado' ? 'Vendido' : ($status == 'Pendiente' ? 'Disponible' : 'Cancelado');
            })->label('Status')->filterable([
                'Pendiente',
                'Entregado',
                'Cancelado',
            ]),

        ];
    }
}
