<?php

namespace App\Http\Livewire\Contratos;

use App\Http\Livewire\Invoices\Includes\OrderConfirmTrait;
use App\Models\Contrato;
use Livewire\Component;

class EditContrato extends Component
{
    public $contrato_id, $contrato, $chasis;
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
        $this->chasis=$this->contrato->invoice->chasis;
        $this->render();
    }
    public function editContrato(){
        $this->validate();


        $this->contrato->save();

        if($this->chasis){
            $this->chasis->update([
                'tipo'=>$this->contrato->tipo,
                'marca'=>$this->contrato->marca,
                'modelo'=>$this->contrato->modelo,
                'color'=>$this->contrato->color,
                'chasis'=>$this->contrato->chasis,
                'year'=>$this->contrato->year,
                'placa'=>$this->contrato->placa,
            ]);
        }

        $this->emit('showAlert','Contrato editado exitosamente','success');
    }

}
