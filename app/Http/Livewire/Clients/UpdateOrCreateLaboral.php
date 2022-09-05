<?php

namespace App\Http\Livewire\Clients;

use App\Models\Laboral;
use Livewire\Component;

class UpdateOrCreateLaboral extends Component
{
    public $client, $form;

    public function rules()
    {
        return [
            'form.activity' => 'required',
            'form.profesion' => 'required',
            'form.condition' => 'required',
            'form.company' => 'required_if:form.condition,Dependiente|string|max:75',
            'form.address' => 'required_if:form.condition,Dependiente|string|max:75',
            'form.phone' => 'required_if:form.condition,Dependiente|string|max:15',
            'form.salary' => 'required|numeric',
            'form.start_at' => 'required_if:form.condition,Dependiente',
        ];
    }

    public function mount($client)
    {
        $this->form=$client->laboral? $client->laboral->toArray() : [];
        $this->form['id']=optional($client->laboral)->id;
    }

    public function render()
    {
        return view('livewire.clients.update-or-create-laboral');
    }

    //create or update laboral and redirect to client show
    public function createLaboral()
    {
        $this->validate();
        $this->client->laboral()->updateOrCreate(['id'=>$this->form['id']],$this->form);
        return redirect()->route('clients.show',$this->client);
    }
}
