<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use NumberFormatter;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Ver Facturas'])->only(['index', 'show']);
        $this->middleware(['permission:Crear Facturas'])->only('create');
        $this->middleware(['permission:Cobrar Facturas'])->only('orders');
    }

    public function index()
    {
        return view('pages.invoices.index');
    }
    public function create()
    {
        request()->session()->put('details', []);
        return view('pages.invoices.create');
    }
    public function orders()
    {
        return view('pages.invoices.orders');
    }
    public function show(Invoice $invoice)
    {
        $invoice = $invoice;
        return  view('pages.invoices.show', compact('invoice'));
    }
    public function cuotas(Invoice $invoice)
    {
        $invoice = $invoice;
        return  view('pages.invoices.cuotas', compact('invoice'));
    }
    public function amortizacion($invoice_id)
    {
        $invoice = Invoice::find($invoice_id);
        $payment = $invoice->payment;
        $cuotas = $invoice->cuotas()->orderBy('fecha')->get();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pages.invoices.amortizacion-pdf', compact('cuotas', 'invoice', 'payment'));
        return $pdf->download('amortización ' . $invoice->number . '.pdf');
    }
    public function pendientes()
    {
        return view('pages.invoices.pendientes');
    }
    public function carta(Invoice $invoice, $to = 'Seguro')
    {
        $payment = $invoice->payment;
        $store = $invoice->store;
        $cuotas = $invoice->cuotas()->orderBy('fecha')->get();
        $contact = $invoice->client->contact;
        $contrato = $invoice->contrato;
        $pdf = App::make('dompdf.wrapper');
        $f = new NumberFormatter("es", NumberFormatter::SPELLOUT);
        $meses = ['January' => 'ENERO', 'february' => 'FEBRERO', 'March' => 'MARZO', 'May' => 'MAYO', 'June' => 'JUNIO', 'July' => 'JULIO', 'August' => 'AGOSTO', 'September' => 'SEPTIEMBRE', 'October' => 'OCTUBRE', 'November' => 'NOVIEMBRE', 'December' => 'DICIEMBRE'];
        $user = $store->users()->where('users.id', '!=', 1)->first();
        if ($to == 'Seguro') {
            $pdf->loadView('pages.invoices.carta_seguro', get_defined_vars());
        } else {
            $pdf->loadView('pages.invoices.carta_digesset', get_defined_vars());
        }

        return $pdf->download('Carta de ruta para '.$to.' ' . $invoice->number . '.pdf');
    }
}
