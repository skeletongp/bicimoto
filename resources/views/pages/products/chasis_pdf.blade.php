<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Listado de Chasis</title>
</head>
<style>
    * {
        font-family: Arial, Helvetica, sans-serif;
    }

    @page {
        size: 215.9mm 279.4mm;
        margin: 5mm;
        margin-top: 12mm;


    }

    body {
        text-align: center;
        color: #222;
    }

    table {
        width: 100%;
        border-spacing: 2mm 0;
        text-align: left;
        border-collapse: separate;
    }

    td {
        border: dotted 1px #000;
        height: 50.6mm;
        border-radius: 15px;
        text-align: center;
    }
</style>

<body>
    <table style="width: 100%">
        @foreach ($chasis as $chasis)
            <tr>
                <td style="width:50%">
                    <span style="font-weight: bold; font-size:xx-large">{{ $chasis->code }}</span><br>
                    <span style="font-weight: bold; font-size:large">{{ $chasis->marca }} {{ $chasis->modelo }}
                        {{ $chasis->year }}</span><br>
                    <span style="font-weight: bold; font-size:medium">{{ $chasis->chasis }} </span><br>
                </td>
                <td>
                    <div style="transform:scale(0.5)">
                        <span style="font-weight: bold; font-size:medium">{{ $chasis->code }}</span><br>
                        <span style="font-weight: bold; font-size:small">{{ $chasis->marca }} {{ $chasis->modelo }}
                            {{ $chasis->year }}</span><br>
                        <span style="font-weight: bold; font-size:x-small">{{ $chasis->chasis }} </span><br>
                    </div>
                </td>
            </tr>
        @endforeach


    </table>
</body>

</html>
