<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use App\Models\Invoice;
use Carbon\Carbon;
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
    public function vencidas()
    {
        $cuotas=
        Cuota::where('cuotas.fecha', '<', date('Y-m-d'))
        ->where('cuotas.status', '!=', 'pagado')
        ->orderBy('cuotas.fecha')
        ->leftjoin('clients', 'clients.id', '=', 'cuotas.client_id')
        ->leftjoin('invoices', 'invoices.id', '=', 'cuotas.invoice_id')
        ->leftJoin('contacts', 'contacts.id', '=', 'clients.contact_id')
        ->where('invoices.place_id', getPlace()->id)
        ->where('invoices.deleted_at', null)
        ->selectRaw('count(cuotas.id) as cant, sum(capital) as capital, sum(interes) as interes, sum(mora) as mora, sum(debe) as debe, contacts.fullname as client, contacts.phone as phone, invoices.number')
        ->groupBy('clients.id')
        ->orderBy('contacts.name')
        ->get();
        ;
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pages.invoices.vencidas-pdf', compact('cuotas'));
        return $pdf->download('Cuotas vencidas ' .date('d/m/Y') . '.pdf');
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
        $chasis = $invoice->chasis;
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
