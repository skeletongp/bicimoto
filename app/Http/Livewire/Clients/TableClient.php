<?php

namespace App\Http\Livewire\Clients;

use App\Http\Classes\NumberColumn as ClassesNumberColumn;
use App\Http\Helper\Universal;
use App\Models\Client;
use App\Models\User;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TableClient extends LivewireDatatable
{

    public $headTitle = "Clientes Registrados";
    public  $hideable = "select";
    public $padding = "px-2";

    public function builder()
    {   
        $store= auth()->user()->store;
        $clients=Client::where('clients.store_id',$store->id)
        ->leftJoin('contacts','contacts.id','=','clients.contact_id')
        ->leftJoin('invoices','invoices.client_id','=','clients.id')
        ->groupBy('clients.id');
        $this->perPage=10;
        return $clients;
    }

    public function columns()
    {
        return [
            Column::callback('id', function ($id)  {
                return view('components.view',['url'=>route('clients.show',$id),'id'=>$id]);
              
            }),
            Column::callback(['contacts.fullname'], function ( $name)  {
                $name=ellipsis($name,20);
                return $name;
            })->label('Nombre')->searchable(),
            Column::name('contacts.email')->label('Correo Electrónico')->searchable(),
            ClassesNumberColumn::name('limit')->label('Crédito')->searchable()->formatear('money'),
            ClassesNumberColumn::name('clients.debt')->label('Deuda')->searchable()->formatear('money'),
            Column::name('contacts.phone')->label('Teléfono')->searchable()->headerAlignCenter(),
            Column::name('contacts.cedula')->label('Cédula')->searchable()->headerAlignCenter(),
            Column::callback(['created_at', 'id'], function ($created, $id)  {
                return view('pages.clients.actions', ['client_id'=>$id]);
            })->label('Acciones')->headerAlignCenter(), 
            
            
        ];
    }
}
