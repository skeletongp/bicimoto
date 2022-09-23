<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function __construct() {
        $this->middleware(['permission:Ver Clientes']);
    }

    public function index()
    {
        return view('pages.clients.index');
    }
    public function show($client_id)
    {
        $client=Client::whereId($client_id)->first();
        $conyuge=$client->conyuge;
        $laboral=$client->laboral;
        $crediticio=$client->crediticio;
        
        return view('pages.clients.show',get_defined_vars());
    }

    public function invoices($client_id)
    {
        return view('pages.clients.client-invoice', ['client_id'=>$client_id]);
    }

    public function paymany(Request $request, $cuotas){
        $cuotas=$cuotas;
        return view('pages.clients.paymany', compact('cuotas'));
    }
}
