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
            margin-left: 1cm;
            margin-right: 1cm;
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
            <p>
                {{__('Santo Domingo, D. N.,')}} <br>
                {{date('d').' de '.ucfirst(mb_strtolower($meses[date('F')])).' de '.date('Y')}}
            </p>
            <h1 style="text-align: center; font-size:large">CARTA DE RUTA</h1>
            <p>
                Señores <br>
               <b>Dirección General de Tránsito y Transporte Terrestre (DIGESETT)</b><br>
               Ciudad
            </p>
            <p>
                Distinguidos Señores:
            </p>
            <p> Por medio de la presente certificamos que los documentos originales del vehículo que se describe  continuación, perteneciente {{ $contact->genre == 'Masculino' ? 'al señor' : 'a la señora' }}
                <b style="text-transform: uppercase">{{ $contact->fullname }}</b>, {{ $contact->nacionality }}, mayor de
                edad, {{ $contact->civil_status }}, titular de la Cédula de Identidad y Electoral No.
                <b>{{ $contact->cedula }}</b> se encuentan en la <b>Dirección General de Impuestos Internos (DGII)</b> para fines de traspaso, y aún no han sido remitidos a esta compañía. <br>

            <table style="width: 100%">
                <tr>
                    <td colspan="2"><b>TIPO:</b> {{ $chasis->tipo }}</td>
                </tr>
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
                <b>NOTA:</b> El uso indebido es plena responsabilidad del adquiriente, así como los daños a terceros que se produzcan, ya sea por accidente o por cualquier otro motivo. Esta comunicación es válida sólo a fines de circulación del vehículo descrito; cualquier otro uso dado al mismo requiere al debida autorización por parte de la empresa. 
            </p>

            <p>
                Estaremos muy agradecidos con el buen trato brindado al portador de esta carta de ruta. Para cualquuier información adicional, favor comunicarse con {{$user->fullname}} al teléfono <b>{{ $user->phone }}</b> o al correo electrónico <b>{{ $user->email }}</b>.
            </p>
           
            <p>
                Emitida en la <b style="text-transform: uppercase">{{ $store->address }}</b>, a los <b style="text-transform: uppercase">{{$f->format(date('d'))}} ({{date('d')}})</b> del mes de <b style="text-transform: uppercase"> {{$meses[date('F')]}}</b> del año <b style="text-transform: uppercase"> {{$f->format(date('Y'))}} ({{date('Y')}})</b>.
            </p>
            <p>
                Con las gracias anticipadas por la atención prestada, les saluda, <br>
                Atentatamente,
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
