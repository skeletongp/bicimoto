<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $invoice->number }}</title>

    <style>
        @page {
            size: 215.9mm 279.4mm;
        }

        * {
            background-color: transparent !important;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10;
            border: 1px solid #eee;
            line-height: 1.5;
            color: #000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }


        body h1 {
            font-weight: bold;
            margin-bottom: 0px;
            padding-bottom: 0px;
            color: #000;
        }

        body h3 {
            font-weight: 300;
            margin-top: 10px;
            margin-bottom: 20px;
            font-style: italic;
            color: #555;
        }

        body a {
            color: #06f;
        }

        footer {
            position: fixed;
            left: 0px;
            right: 0px;
            bottom: 2
        }
        .invoice-box{
            margin-left: 1.5cm;
            margin-right: 1.5cm;
        }
    </style>
</head>



<body>

    <div class="invoice-box" id="box" style="position: relative;">
        <div style="text-align: justify">
            <div style="text-align: center; width:220px;">
                <img src="{{ $store->logo }}" alt=" "
                    style=" width:max-content; max-width: 200px;  max-height: 100px" />
                <br>
                <span style="text-transform: uppercase"> {{ $store->lema }}</span>
                <br>
                <span style="font-size: small">RNC: {{ $store->rnc }}</span>
            </div>
        </div>
        <main style="margin: 0.5cm; text-align:justify;">
            <h1 style="text-align: center; font-size:x-large">CARTA DE RUTA</h1>
            <p> Por medio de la presente hacemos constar que
                {{ $contact->genre == 'Masculino' ? 'el señor' : 'la señora' }}
                <b style="text-transform: uppercase">{{ $contact->fullname }}</b>, {{ $contact->nacionality }}, mayor de
                edad, {{ $contact->civil_status }}, titular de la Cédula de Identidad y Electoral No.
                <b>{{ $contact->cedula }}</b> ha adquirido de <b
                    style="text-transform: uppercase">{{ $store->name }}</b> el vehículo que se describe a
                continuación bajo financiamiento de una duración de <b>{{ $contrato->cuotas }} cuotas</b>, por tal
                razón, los documentos originales <b>(Placa y Matrícula)</b> se encuentran en nuestro poder, hasta dar
                concluido dicho contrato. <br>

            <table style="width: 100%">
                <tr>
                    <td colspan="2"><b>TIPO:</b> {{ $chasis->tipo }}</td>
                </tr>chasis
                <tr>
                    <td><b>MARCA:</b> {{ $chasis->marca }}</td>
                    <td><b>MODELO:</b> {{ $chasis->modelo }}</td>
                </tr>
                <tr>
                    <td><b>COLOR:</b> {{ $chasis->color }}</td>
                    <td> <b>CHASIS:</b> {{ $chasis->chasis }}</td>
                </tr>
                <tr>
                    <td><b>AÑO:</b> {{ $chasis->year }}</td>
                    <td> <b>PLACA:</b> {{ $chasis->placa }}</td>
                </tr>
            </table>
            </p>
            <p>
                <b>NOTA:</b> El uso indebido es plena responsabilidad del adquiriente, así como los daños a terceros que se produzcan, ya sea por accidente o por cualquier otro motivo. Esta comunicación es válida sólo a fines de circulación del vehículo descrito; cualquier otro uso dado al mismo requiere la debida autorización por parte de la empresa. 
            </p>
            <p>
                Válido por <b>TREINTA (30) días</b> a partir de la fecha de emisión.
            </p>
            <p>
                Emitida en la <b style="text-transform: uppercase">{{ $store->address }}</b>, a los <b style="text-transform: uppercase">{{$f->format(date('d'))}} ({{date('d')}})</b> del mes de <b style="text-transform: uppercase"> {{$meses[date('F')]}}</b> del año <b style="text-transform: uppercase"> {{$f->format(date('Y'))}} ({{date('Y')}})</b>.
            </p>
            <table style="width:max-content; margin:auto">
                <tr>
                    <td style="padding-top: 80px; text-align:center" colspan="2">
                        <div>
                            <b style="text-transform: uppercase">{{$user->fullname}}</b>
                        </div>
                        <div
                            style="border-top: solid 1px #222; padding-top: 4px; width:100%; text-align:center; margin-right: 20px; max-width:60%; margin:auto">
                               GERENTE GENERAL</div>
                    </td>
                </tr>
            </table>
        </main>
    </div>
</body>
</html>
