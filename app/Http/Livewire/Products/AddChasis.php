<?php

namespace App\Http\Livewire\Products;

use App\Models\Chasis;
use Livewire\Component;

class AddChasis extends Component
{
    public $product_id;
    public $chasis=[];
    public $chasisGroup=[];

    protected $rules = [
        'chasis.tipo' => 'required',
        'chasis.marca' => 'required',
        'chasis.modelo' => 'required',
        'chasis.color' => 'required',
        'chasis.year' => 'required',
        'chasis.chasis' => 'required',
    ];
    public function render()
    {
        return view('livewire.products.add-chasis');
    }
    public function addChasis()
    {
        $this->validate();
        array_push($this->chasisGroup, $this->chasis);
        $this->chasis['color'] = '';
        $this->chasis['year'] = '';
        $this->chasis['chasis'] = '';

    }
    public function createChasis(){
        $this->validate([
            'chasisGroup' => 'required',
        ]);
        foreach ($this->chasisGroup as $key => $value) {
            $this->chasisGroup[$key]['product_id']=$this->product_id;
            Chasis::create($this->chasisGroup[$key]);
        }
        $this->resetExcept('product_id');
        $this->emit('showAlert','Chasis registrados','success');
        $this->emit('refreshLivewireDatatable');
    }

}
