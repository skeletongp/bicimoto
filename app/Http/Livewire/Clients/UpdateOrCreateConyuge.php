<?php

namespace App\Http\Livewire\Clients;

use App\Models\Contact;
use Livewire\Component;

class UpdateOrCreateConyuge extends Component
{
    public $client;
    public $form,   $role, $cltDocType, $name, $lastname, $cellphone, $contact_id, $conyuge_id;

    public function mount()
    {
        $this->contact_id = optional($this->client->conyuge)->contact_id;
        $this->conyuge_id = optional($this->client->conyuge)->id;
        $this->form = optional($this->client->conyuge)->contact? $this->client->conyuge->contact->toArray() : [];
        if (optional($this->client->conyuge)->contact) {
            if (strlen($this->client->conyuge->contact->cedula) == 13) {
                $this->cltDocType = 'Cédula';
            } else {
                $this->cltDocType = 'RNC';
            }
        }
    }

    public function render()
    {
        return view('livewire.clients.update-or-create-conyuge');
    }
    function rules()
    {
        return [
            'form.name' => 'required|max:50',
            'form.lastname' => 'required|max:50',
            'form.email' => 'required|email|max:100|unique:contacts,email,'.$this->contact_id,
            'form.address' => 'required|string|max:100',
            'form.phone' => 'string|max:25',
            'form.nacionality' => 'required|string|max:50',
            'form.genre' => 'required|string|max:25',
            'form.civil_status' => 'required|string|max:25',
            'form.cedula' => 'required|string|max:25',
            'form.cellphone' => 'required|string|max:25',
            'cltDocType' => 'required',
        ];
    }
    public function createConyuge()
    {

        $store = auth()->user()->store;
        $this->validate();
        $client = $this->client;
        $conyuge = $client->conyuge()->updateOrCreate([
            'id' => $this->conyuge_id,
        ], []);
        $contact = Contact::updateOrCreate(['id' => $this->contact_id], $this->form);
        $conyuge->contact()->associate($contact);
        $conyuge->save();
        return redirect(request()->header('Referer'));
    }
}
