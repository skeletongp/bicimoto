<?php

namespace App\Http\Livewire\Anticipos;

use App\Models\Anticipo;
use Livewire\Component;

class EditAnticipo extends Component
{
    public $anticipo_id, $anticipo;
    public $amount;

    protected $listeners = ['modalOpened'];


    public function render()
    {
        return view('livewire.anticipos.edit-anticipo');
    }

    public function modalOpened()
    {
        $this->anticipo = Anticipo::find($this->anticipo_id);
    }

    public function updateAnticipo()
    {
        $this->validate([
            'amount' => 'required|numeric|min:0',
        ]);
        $prevSaldo = $this->anticipo->saldo;
        $this->anticipo->update([
            'saldo' => $this->amount
        ]);

        $diff = $prevSaldo - $this->amount;
        $place = auth()->user()->place;
        $anticipo = $place->findCount('206-01');

        if ($diff > 0) {
            setTransaction('Ajuste de anticipo ' . $this->anticipo->client->contact->fullname, $this->anticipo->client->code, $diff, $anticipo, $place->cash(), 'Cobrar Facturas');
        } else {
            setTransaction('Ajuste de anticipo ' . $this->anticipo->client->contact->fullname, $this->anticipo->client->code, abs($diff), $place->cash(), $anticipo,  'Cobrar Facturas');
        }
        $this->emit('refreshLivewireDatatable');
    }
}
