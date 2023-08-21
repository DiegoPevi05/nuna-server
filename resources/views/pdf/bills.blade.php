<!DOCTYPE html>
<html>
<head>
    <title>Bill</title>
    <style>
        /* Add your styling here */
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Numero de Boleta N° {{ str_pad($id, 7, '0', STR_PAD_LEFT) }}</h2>
        <h2>NUNA</h2>
        <p>{{ date('F j, Y') }}</p>
    </div>
    <p>Boleta para: {{ $meet->user->name }}</p>
    <table>
        <tr>
            <th>#</th>
            <th>Nombre del Servicio</th>
            <th>Cantidad</th>
            <th>Precio Unit.</th>
            <th>Precio Total</th>
        </tr>
        <tr>
            <td>1</td>
            <td>{{ $meet->service->name }}</td>
            <td>1</td>
            <td>{{ $meet->price }}</td>
            <td>{{ $meet->price }}</td>
        </tr>
        <tr>
            <td colspan="4">Descuento</td>
            <td>{{ $meet->discount }}</td>
        </tr>
        <tr>
            <td colspan="4">Precio con descuento</td>
            <td>{{ $meet->discounted_price }}</td>
        </tr>
    </table>
    <p>Dia del Meet: {{ $meet->date_meet }}</p>
    <p>Enlace del Meet: {{ $meet->link_meet }}</p>
    <p>Duracion del Meet: {{ $meet->duration }}</p>
</body>
</html>
