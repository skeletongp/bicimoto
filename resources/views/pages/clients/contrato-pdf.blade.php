<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $invoice->number }}</title>
    @php
        if ($invoice->store_id == 18 && $invoice->rest < 1) {
            $img = asset('/images/sello.png');
        } else {
            if ($invoice->status == 'PENDIENTE') {
                $img = asset('/images/pendiente.png');
            } else {
                $img = asset('/images/pagado.png');
            }
        }
        if ($invoice->type == 'cotize') {
            $img = asset('/images/cotizacion.png');
        }

    @endphp
    <style>
        @page {
            size: 215.9mm 355.6mm;
        }

        * {
            background-color: transparent !important;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            border: 1px solid #eee;
            color: #000;
            padding-top: 15px;
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
            margin-bottom: 20px;
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

        .invoice-box table tr.information table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            text-align: left;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
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

    <div class="invoice-box" id="box"
        style="position: relative; font-family:'Times New Roman', Times, serif; font-size:12; line-height:1.5">

        <main style="padding:10px; text-align:justify;">
            <b>ACTO NÚMERO _________________, (____________________) FOLIO NÚMERO (_____________)</b> <br>
            En la ciudad de Sabana Grande de Palenque, municipio homónimo, Provincia San Cristóbal, República
            Dominicana, a los <b style="text-transform: uppercase">{{ $dia }}
                ({{ date_format($date, 'd') }}) días del mes de {{ $mes }} del año {{ $año }}
                ({{ date_format($date, 'Y') }})</b>, por ante mí, <b>LIC. DENNIS ANASTASIO REYES AQUINO</b>, dominicano,
            casado, mayor de edad, titular de la cédula de Identidad y Electoral No. <b>002-0069529-4</b>, Abogado
            Notario Públio de los del número pra el Municipio de Saban Grande de Palenque, matriculado en el Colegio
            Dominicano de Notarios, Inc. con el <b>No. 7575</b>, con estudio profesional instalado en la Calle Mella,
            No. 96, sector Camagüey, Municipio Sabana Grande de Palenque, Provincia San Cristóbal, República Dominicana,
            asistido de los testigos que la final de este acto serán nombrados coompareció personalmente
            {{ $contact->genre == 'Masculino' ? 'el señor' : 'la señora' }} <b
                style="text-transform: uppercase">{{ $contact->fullname }}</b>, {{ $contact->nacionality }}, mayor de
            edad, {{ $contact->civil_status }}, titular de la Cédula de Identidad y Electoral No.
            <b>{{ $contact->cedula }}</b>, con domicilio y residencia citado en {{ $contact->address }}. Encontrándome
            en mi despacho y en regular ejercicio de mis funciones, comparacieron libre y voluntariamente, los señores
            de la razón social <b style="text-transform: uppercase"> {{ $store->name }}</b>, Registro Nacional de
            Contribuyente (RNC) No. <b>{{ $store->rnc }}</b>, con domicilio social en {{ $store->address }},
            debidamente representada por su Gerente General, el señor <b
                style="text-transform: uppercase">{{ $user->fullname }}</b>, dominicano, mayor de edad, titular de la
            cédula de Identidad y Electoral No. <b style="text-transform: uppercase">{{ $user->cedula }}</b>,
            domiciliado y residente en la Calle Romualdo Tejeda, No. 29, de esta misma ciudad, y como personas jurídicas
            cada uno ante el presente documento y en lo adelante se llamará <b>EL ACRREDOR</b>, y <b>EL DEUDOR</b> del
            presente <b>PAGARÉ NOTARIAL</b> me declaran que convienen y pactan lo siguiente:

            <br> <br>
            <b>PRIMERO:</b> Que cada
            uno de ellos comparecen libre y voluntariamente y sus expresiones son la más pura y fiel expresión de su
            voluntad;

            <br><br>

            <b>SEGUNDO:</b> Que <b> EL DEUDOR </b> <b
                style="text-transform: uppercase">{{ $contact->fullname }}, RECONOCE ADEUDAR</b> a <b> EL ACREEDOR </b>
            la razón social <b style="text-transform: uppercase"> {{ $store->name }}</b> la suma de <b
                style="text-transform: uppercase"> {{ $f->format($invoice->payment->rest) }} PESOS DOMINICANOS
                (RD${{ formatNumber($invoice->payment->rest) }}) </b>, en efectivo e intereses, por concepto de
            préstamo que éste último le hace en este mismo instante en presencia del infrascrito Notario;

            <br> <br><b>TERCERO:</b> Que hasta el pago integral de dicho préstamo <b>EL DEUDOR</b>
            {{ $contact->genre == 'Masculino' ? 'el señor' : 'la señora' }} <b
                style="text-transform: uppercase">{{ $contact->fullname }}</b>, se obliga a cumplir de la manera
            siguiente y a pagar o entregar en <b>CUOTAS</b> los días {{ $dia }}
            ({{ date_format($date, 'd') }}) de cada mes en manos de <b>EL ACREEDOR</b> la razón social <b
                style="text-transform: uppercase"> {{ $store->name }}</b> y su representante el señor <b
                style="text-transform: uppercase">{{ $user->fullname }}</b> la suma de <b
                style="text-transform: uppercase">({{ $f->format($cuota->debe) }}) PESOS DOMINICANOS
                (RD${{ formatNumber($cuota->debe) }})</b> , por un periodo de <b
                style="text-transform: uppercase">{{ $f->format($contrato->cuotas) }} ({{ $contrato->cuotas }})</b>
            cuotas continuas, comenzando a pagar el <b style="text-transform: uppercase"> {{ $cuotaDia }}
                ({{ date_format($cuota->fecha, 'd') }}) DEL MES DE {{ $cuotaMes }} del año
                {{ $cuotaAño }}</b> hasta finalizar su compromiso y depositará en manos y domicilio de EL ACREEDOR
            la razón social <b style="text-transform: uppercase"> {{ $store->name }}</b> y su representante el señor
            <b style="text-transform: uppercase">{{ $user->fullname }}</b> cada mes de su obligación de pago, esta
            sería la fecha real del pago del préstamo;


            <br> <br>
            <b>CUARTO:</b> Que <b>EL DEUDOR</b>
            {{ $contact->genre == 'Masculino' ? 'el señor' : 'la señora' }} <b
                style="text-transform: uppercase">{{ $contact->fullname }}</b> se obliga a pagar el monto total de
            dicho préstamo, a <b>EL ACREEDOR</b> la razón social <b style="text-transform: uppercase">
                {{ $store->name }}</b> y su representante el señor<b
                style="text-transform: uppercase">{{ $user->fullname }}</b>, quien acepta, el plazo descrito
            anteriormente en el artículo Tercero;


            <br> <br><b>QUINTO:</b> Que <b>EL DEUDOR</b>
            {{ $contact->genre == 'Masculino' ? 'el señor' : 'la señora' }} <b
                style="text-transform: uppercase">{{ $contact->fullname }}</b> podrá liberarse por anticipado de la
            totalidad o de fracciones del capital adeudado previo pago del mismo al vencimiento de lo acordado en el
            artículo Tercero;


            <br> <br>

            <b>SEXTO</b>: Que <b>EL DEUDOR</b> {{ $contact->genre == 'Masculino' ? 'el señor' : 'la señora' }} <b
                style="text-transform: uppercase">{{ $contact->fullname }}</b> en garantía y hace entrega de la
            matricula o título provisional del producto @if ($contrato->chasis)
                <b>TIPO:</b> {{ $contrato->tipo }} <b>COLOR:</b> {{ $contrato->color }}, <b>AÑO:</b>
                {{ $contrato->year }}, <b>MARCA:</b> {{ $contrato->marca }}, <b>MODELO:</b> {{ $contrato->modelo }},
                <b>CHASIS:</b> {{ $contrato->chasis }}
            @endif objeto de este contrato, según consta en la matricula o título provisional del
            mismo y todos sus bienes muebles e inmuebles presentes y futuros en garantía de dicha deuda, que podrá ser
            cobrada sin necesidad de intervención judicial al tenor de lo estipulado en el artículo 1134 del Código
            Civil, que indica que las convenciones legalmente formadas tienen fuerza de ley para quienes las han hecho,
            así como en virtud del artículo 545 del Código de Procedimiento Civil.


            <br> <br>
            <b>SEPTIMO:</b> <b>EL DEUDOR</b>
            {{ $contact->genre == 'Masculino' ? 'el señor' : 'la señora' }} <b
                style="text-transform: uppercase">{{ $contact->fullname }}</b> se compromete a pagar a <b>EL ACREEDOR</b>
            la razón social <b style="text-transform: uppercase"> {{ $store->name }}</b> y su representante el señor
            <b>ROBERT ALBERTO NUÑEZ</b>, por la suma indicada, con un interés de un <b
                style="text-transform: uppercase">{{ $f->format($contrato->place->preference->mora_rate) }} POR CIENTO
                ({{ $contrato->place->preference->mora_rate }}%)</b> sobre la cuota atrasada, si llegase a incumplir el
            compromiso de pago con las cuotas mensuales que deberá ser pagado junto con la suma del préstamo el día de
            pago de la única cuota establecida y de no cumplir será sumatoria al momento de su saldo.

            <br> <br><b>OCTAVO:</b>
            Que, para la ejecución de las presentes condiciones, las Partes eligen domicilio en sus respectivos
            indicados y someterán sus diferencias, si las hubiera, a los tribunales competentes de la República
            Dominicana si fuere necesario.

            @if ($contrato->garante)
            <br> <br><b>NOVENO:</b>
            Queda instrumentado como <b>GARANTE</b> del presente acuerdo {{ $relacionado->contact->genre == 'Masculino' ? 'el señor' : 'la señora' }} <b
                style="text-transform: uppercase">{{ $relacionado->contact->fullname }}</b> con cédula de identidad y electoral <b> {{ $relacionado->contact->cedula }}</b>, con domicilio y residencia en <b>{{ $relacionado->contact->address }}</b>, quien se compromete a avalar y representar a <b>EL DEUDOR</b> y, en caso de éste faltar a su compromiso de pago, se obliga a pagar el total de las obligaciones contraídas en nombre de <b>EL DEUDOR</b>, con los intereses y demás costos que se generen, sin perjuicio de las acciones legales que correspondan.
            @endif

            <br> <br><b>HECHO, REDACTADO, TRANSCRITO Y FIRMADO</b> en dos originales, en mi
            presencia y en la de los señores <b style="text-transform: uppercase">{{$contrato->garante?$relacionado->contact->fullname.' (GARANTE)':'ANA MERCEDES MONTAÑO (TESTIGO)'}} </b> y <b>JOHANNA GISELL RIVERA (TESTIGO)</b>,
            dominicanos, mayores de edad, solteros, titulares de las cédulas de identidad y electoral:
            <b>{{$contrato->garante? $relacionado->contact->cedula:'001-0462029-9'}}</b> y <b>001-1336056-4</b> respectivamente, ambos de este domicilio y residencia, testigos
            instrumentales requeridos al efecto, libres de tachas y excepciones que establece la ley, personas a quienes
            también doy fe conocer, quienes después de aprobarlo, comparecientes y testigos, lo han firmado y rubricado
            junto conmigo y ante mí, notario infrascrito que Certifica y da fe., considerándolo <b> BUENO Y VALIDO</b>.

        </main>
        <br>


        <table>
            <tr>
                <td style="padding-top: 30px; text-align:center">
                    <div>
                        <b style="text-transform: uppercase"> {{ $store->name }}</b><br>
                        <b style="text-transform: uppercase">{{ $user->fullname }}</b>
                    </div>
                    <div
                        style="border-top: solid 1px #222; padding-top: 4px; width:100%; text-align:center; margin-right: 20px">
                        ACREEDOR</div>
                </td>
                <td style="padding-top: 30px; text-align:center">
                    <div>
                        <br>
                        <b style="text-transform: uppercase"> {{ ellipsis($contact->fullname, 50) }}</b><br>
                    </div>
                    <div
                        style="border-top: solid 1px #222; padding-top: 4px; width:100%; text-align:center; margin-left: 10px">
                        DEUDOR</div>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 80px; text-align:center">
                    @if ($contrato->garante)
                        <div style="text-transform: uppercase">
                            <b>{{ ellipsis($relacionado->contact->fullname, 50) }}</b>
                        </div>
                        <div
                        style="border-top: solid 1px #222; padding-top: 4px; width:100%; text-align:center; margin-right: 20px">
                        GARANTE</div>
                    @else
                        <div>
                            <b>ANA MERCEDES MONTAÑO</b>
                        </div>
                        <div
                        style="border-top: solid 1px #222; padding-top: 4px; width:100%; text-align:center; margin-right: 20px">
                        TESTIGO</div>
                    @endif

                </td>
                <td style="padding-top: 80px; text-align:center">
                    <div>
                        <b style="text-transform: uppercase"> JOHANNA GISELL RIVERA</b><br>
                    </div>
                    <div
                        style="border-top: solid 1px #222; padding-top: 4px; width:100%; text-align:center; margin-left: 10px">
                        TESTIGO</div>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 80px; text-align:center" colspan="2">
                    <div>
                        <b>LIC. DENNIS ANASTASIO REYES AQUINO</b>
                    </div>
                    <div
                        style="border-top: solid 1px #222; padding-top: 4px; width:100%; text-align:center; margin-right: 20px; max-width:60%; margin:auto">
                        ABOGADO-NOTARIO</div>
                </td>

            </tr>
        </table>


    </div>



</body>


</html>
<script type="text/javascript">
    alert('hola');
</script>
