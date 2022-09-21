<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Livewire\General\Authorize;
use App\Http\Livewire\Invoices\ShowIncludes\ShowAttach;
use App\Http\Livewire\Invoices\ShowIncludes\Showclient;
use App\Http\Livewire\Invoices\ShowIncludes\ShowCredit;
use App\Http\Livewire\Invoices\ShowIncludes\ShowPayments;
use App\Http\Livewire\Invoices\ShowIncludes\ShowProducts;
use App\Http\Livewire\Invoices\ShowIncludes\ShowResume;
use App\Http\Livewire\Invoices\ShowIncludes\ShowUsers;
use App\Models\Image;
use App\Models\Invoice;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Livewire\WithFileUploads;
use NumberFormatter;

class InvoiceShow extends Component
{
    use Showclient, Authorize, ShowProducts, ShowUsers, ShowPayments, WithFileUploads, ShowCredit, ShowResume, ShowAttach;

    public Invoice $invoice;
    public $includeName = "showresume";
    public $includeTitle = 'Cliente';
    public $action;
    public $lastPayment;
    public $details;
    public $attachs, $document;

    protected $rules = [
        'client' => 'required',
        'seller' => 'required',
        'contable' => 'required',
        'payment' => 'required',
        'det' => 'required',
        'prods.*.code' => 'required',
    ];
    protected $listeners = ['updateInvoiceClient', 'reloadEdit' => 'render'];
    protected $queryString = ['includeName', 'includeTitle'];
    public function mount()
    {
        $this->loadData($this->includeName);
        $this->initCreditnote();
    }
    public function render()
    {
        return view('livewire.invoices.invoice-show');
    }
    public function setIncludeElement($name, $title)
    {
        $this->loadData($name);
        $this->includeName = $name;
        $this->includeTitle = $title;
        $this->render();
        if ($name == 'showclient') {
            return redirect()->route('invoices.show', [$this->invoice, 'includeName' => 'showclient', 'includeTitle' => 'Cliente']);
        }
    }
    public function loadClient()
    {
        $store = auth()->user()->store;
        $this->clients = clientWithCode($store->id);
        $client = $this->invoice->client
            ->load('contact');
        $contact = $client->contact;
        $this->client = [
            'name' => $contact->fullname,
            'address' => $contact->address,
            'phone' => $contact->phone,
            'email' => $contact->email,
            'rnc' => $contact->cedula ?: 'N/D',
            'id' => $client->id,
            'balance' => '$' . formatNumber($client->limit),
            'gasto' => '$' . formatNumber($client->payments()->sum('payed')),
            'limit' => $client->limit,
            'contact' => $contact->contact,
        ];
    }
    public function loadProducts()
    {
        $this->details = $this->invoice->details;
    }
    public function loadSeller()
    {
        $this->seller = $this->invoice->seller->toArray();
    }
    public function loadContable()
    {
        $this->contable = $this->invoice->contable->toArray();
    }
    public function loadAttach()
    {
        $images = Image::get();
        //$this->attachs = $this->invoice->images;
        $this->attachs = $images;
    }

    public function loadPayments()
    {
        $this->banks = auth()->user()->store->banks()->pluck('bank_name', 'id');
        $this->payment['efectivo'] = 0;
        $this->payment['tarjeta'] = 0;
        $this->payment['transferencia'] = 0;
        $this->lastPayment = $this->invoice->payments()->orderBY('id', 'desc')->first();
        $inv = $this->invoice->client->invoices()->where('id', '<', $this->invoice->id)->where('rest', '>', 0)->first();
        $user = auth()->user();
        if ($inv && !$user->hasRole('Administrador')) {
            $this->cobrable = false;
        }
    }
    public function loadResume()
    {
    }
 
    public function loadData($includeName)
    {
        switch ($includeName) {
            case 'showclient':
                $this->loadClient();
                break;
            case 'showproducts':
                $this->loadProducts();
                break;
            case 'showseller':
                $this->loadSeller();
                break;

            case 'showcontable':
                $this->loadContable();
                break;
            case 'showpayments':
                $this->loadPayments();
                break;
            case 'showattach':
                $this->loadAttach();
                break;
            case 'showdocuments':
                $this->loadDocuments();
                break;
            case 'showresume':
                $this->loadResume();
                break;
        }
        $this->render();
    }
}
