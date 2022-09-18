<?php

namespace App\Http\Livewire\General;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\Paginator;

class Amortizar extends Component
{
    use WithPagination;
    public $periodo='mensual', $monto, $interes, $cuotas;
    public $amortizar=false;
    
    protected $rules=[
        'periodo' => 'required',
        'monto' => 'required|numeric|min:1',
        'interes' => 'required',
        'cuotas' => 'required|numeric|min:1',
    ];
    public function render()
    {
        $pagares=null;
        if ($this->amortizar) {
            $pagares=$this->amortizar();
        }
        $this->amortizar=false;
        return view('livewire.general.amortizar',['pagares'=>$pagares]);
    }
    public function limpiar(){
        $this->reset();
        $this->periodo='mensual';
        
    }
    public function amortizar(){
        $this->validate();

        $amortizacion=amortizar($this->monto, $this->interes, $this->cuotas, $this->periodo);
        $pagares=collect($amortizacion->pagos);
        $perPage = 10;

        $offset = max(0, ($this->page - 1) * $perPage);
        
        // need one more here so the simple paginatior knows
        // if there are more pages left
        $items = $pagares->slice($offset, $perPage + 1);

        $paginator = new Paginator($items, $perPage, $this->page);
        
        
        return $paginator;
    }
}
