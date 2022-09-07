<?php

namespace App\Http\Livewire\Contratos;

use App\Http\Livewire\Invoices\Includes\OrderConfirmTrait;
use App\Models\Contrato;
use Livewire\Component;

class EditContrato extends Component
{
    public $contrato_id, $contrato;
    use OrderConfirmTrait;
    protected $listeners=['modalOpened'];
    protected $rules=[
        'contrato.tipo'=>'required',
        'contrato.marca'=>'required',
        'contrato.modelo'=>'required',
        'contrato.color'=>'required',
        'contrato.chasis'=>'required',
        'contrato.year'=>'required',
        'contrato.placa'=>'required',
    ];
    public function render()
    {
        return view('livewire.contratos.edit-contrato');
    }
    public function modalOpened(){
        $this->contrato=Contrato::find($this->contrato_id);
        $this->render();
    }
    public function editContrato(){
        $this->validate();
        $this->contrato->save();
        $this->saveContratoPDF($this->contrato);
        $this->emit('showAlert','Contrato editado exitosamente','success');
    }

}
