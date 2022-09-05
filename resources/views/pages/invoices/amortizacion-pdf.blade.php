<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $invoice->number }}</title>

    <style>
        @page {
            size: 215.4mm 255mm;
        }

        * {
            background-color: transparent;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            border: 1px solid #eee;
            color: #777;
            padding-top: 15px;
            border-top: 22px solid #054853;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }


        body h1 {
            font-weight: 300;
            margin-bottom: 0px;
            padding-bottom: 0px;
            color: #000;
        }

        body h3 {
            font-weight: 300;
            margin-top: 10px;
            margin-bottom: 10px;
            font-style: italic;
            color: #555;
        }

        body a {
            color: #06f;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 5px;
            padding-top: 0;

            font-size: 14px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: 20px;
            text-align: left;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .invoice-box table td {
            padding-right: 5px;
            padding-left: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 25px;
            color: #333;
        }

        .invoice-box table tr:nth-child(odd) td {
            background: rgba(224, 224, 224, .5);
            border-bottom: 1px solid rgb(224, 224, 224);
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.information td {
            background: #fff;
            border-bottom: 1px solid #fff;
            text-align: left;


        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
            text-align: left;
            vertical-align: middle;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
            border-spacing: 5rem;
        }

        .total {
            padding: 0px;
            font-size: x-small;
            line-height: 14px;
        }

        .total td {
            padding: -10px !important;

        }



        footer {
            height: 50px;
            margin-bottom: -50px;
            position: fixed;
            left: 0px;
            right: 0px;
            bottom: 0;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div style="position: absolute; right:4; top: 0; color:white; z-index:50; ">
        {{ date_format(now(), ' d/m/Y H:i A') }}
    </div>

    <div class="invoice-box" id="box" style="position: relative;">
        <div style="position: absolute;  top:10px; text-align:center; width: 100%; ">
            <b style="text-transform: uppercase; font-size:x-large; font-weight:bold; padding-bottom:10px">
                {{ $invoice->store->name }}</b><br />
            {!! $invoice->store->rnc ? '<b>RNC  :</b> ' . $invoice->store->rnc . ' /' : '' !!}
            <b>TEL:</b> {{ $invoice->store->phone }} <br>
            <b>EMAIL: </b>{{ $invoice->store->email }}<br />
            <div style="max-width: 40%; margin:auto">{{ ellipsis($invoice->store->address, 80) }}</div>
        </div>
        <table>
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title" style="padding-top:2px">
                                <img src="{{ $invoice->store->logo }}" alt=" "
                                    style=" max-width: 200px; max-height: 100px" />
                            </td>

                        </tr>
                    </table>
                </td>
            </tr>
            <br>
            <tr class="information">
                <td colspan=" 7">
                    <table>
                        <tr>
                            <td colspan="2" style="border-right: .3px solid #ccc; border-bottom: .3px solid #ccc; ">
                                <div>

                                    <table>
                                        <tr>
                                            <td>
                                                <b>Fact. Nº. </b>{{ $invoice->number }}<br />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Monto:</b> ${{ formatNumber($payment->total-$invoice->cuotas->sum('interes')) }}

                                            </td>
                                            <td>

                                                <b>Inicial:</b>
                                                ${{ formatNumber($payment->payed) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Resta:</b>
                                                ${{ formatNumber($payment->rest-$invoice->cuotas->sum('interes')) }}
                                            </td>
                                            <td>
                                                <b>Cuotas:</b>
                                                {{ $invoice->contrato->cuotas }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>Interés:</b>
                                                {{ $invoice->contrato->interes * 12 }}%
                                            </td>
                                            <td>
                                                <b>Utilidad:</b>
                                                $ {{ formatNumber($invoice->cuotas->sum('interes')) }}

                                            </td>
                                        </tr>
                                    </table>



                                </div>
                            </td>
                            <td colspan="2" style="border-bottom: .3px solid #ccc; ">
                                <div style="text-align:right; ">
                                    <b style="text-transform: uppercase">
                                        {{ $invoice->name ?: ($invoice->client->name ?: $invoice->client->contact->fullname) }}</b>
                                    <br>
                                    {!! $invoice->rnc ?:
                                        ($invoice->client->contact->cedula
                                            ? '<b>CED:</b> ' . $invoice->client->contact->cedula . '<br />'
                                            : '') !!}
                                    <b>CEL:</b> {{ $invoice->client->contact->cellphone }} <br>
                                    <b>EMAIL:</b> {{ $invoice->client->contact->email }} <br>
                                    {{ ellipsis($invoice->client->contact->address ?: 'Dirección N/D' ,45)}} 
                                </div>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>

            <tr class="heading" style="padding-top: 10px">
                <td>Fecha</td>
                <td style="text-align:right">
                    Balance
                </td>
                <td style="text-align:right">Capital</td>
                <td style="text-align:right">Interés</td>
                <td style=" text-align:right">Cuota</td>
                <td style=" text-align:right">Estado</td>
                <td style=" text-align:right">F. Pago</td>

            </tr>

            @forelse ($invoice->cuotas as $ind=> $cuota)
                <tr class="list" style="font-size: small; background-color:red">
                    <td style=" width:17%; text-align:left; padding-left:10px;">
                        {{ formatDate($cuota->fecha, 'd/m/Y') }}
                    </td>
                    <td style=" ">${{ formatNumber($cuota->saldo) }}</td>
                    <td style=" text-align:right;">${{ formatNumber($cuota->capital) }}</td>
                    <td style=" text-align:right">${{ formatNumber($cuota->interes) }}</td>
                    <td style="  text-align:right;">
                        ${{ formatNumber($cuota->debe) }}
                    </td>
                    <td
                        style="  text-align:right; text-transform:capitalize; {{ $cuota->status == 'pendiente' ? 'color:red' : 'color:green' }}">
                        {{ $cuota->status }}
                    </td>
                    <td style=" width:20%; text-align:right; padding-left:10px;">
                        {{ $cuota->payed_at ? formatDate($cuota->payed_at, 'd/m/Y') : 'N/D' }}
                    </td>
                </tr>

            @empty
            @endforelse

        </table>

    </div>

    <footer>
        <table style="width: 100%; font-size:x-small">
            <tr>
                <td>IMPRESA POR: {{ auth()->user()->fullname }}</td>
            </tr>
        </table>
    </footer>
</body>


</html>
<script type="text/javascript">
    alert('hola');
</script>
