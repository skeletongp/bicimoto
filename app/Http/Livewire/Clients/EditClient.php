<?php

namespace App\Http\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditClient extends Component
{
    public  $client, $form=[], $client_id;
    public  $avatar, $photo_path;
    use WithFileUploads;

    protected $listeners=['modalOpened'];

    public function render()
    {
        return view('livewire.clients.edit-client');
    }
    function rules()
    {
        return  [
        'form.name' => 'required|max:50',
        'form.lastname' => 'required|max:50',
        'form.email' => 'required|string|max:100|unique:contacts,email,' . $this->form['id'],
        'form.address' => 'required|string|max:100',
        'form.phone' => 'string|max:25',
        'form.nacionality' => 'required|string|max:50',
        'form.genre' => 'required|string|max:25',
        'form.civil_status' => 'required|string|max:25',
        'form.cedula' => 'required|string|max:25',
        'form.cellphone' => 'string|max:25',
        ];
    }
    public function modalOpened(){
        $this->client=Client::find($this->client_id);
        $this->form=$this->client->contact->toArray();
    }
    public function updateClient()
    {
        $this->validate();
        $client=Client::find($this->client['id']);
        if ($this->photo_path) {
            $client->image()->updateOrCreate(['imageable_id'=>$client->id],[
                'path' => $this->photo_path
            ]);
        }
        $contact=$client->contact;
        $contact->update($this->form);
        $this->emit('refreshLivewireDatatable');
        $this->emit('showAlert', 'Cliente Actualizado Exitosamente', 'success');
    }

    public function updatedAvatar()
    {
        
        $path = cloudinary()->upload($this->avatar->getRealPath(),
        [
            'folder' => 'carnibores/avatars',
            'transformation' => [
                      'width' => 250,
             ]
        ])->getSecurePath();
        $this->photo_path = $path;
    }
    
}
