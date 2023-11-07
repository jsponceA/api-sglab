<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>REPORTE DE RESULTADOS</title>
</head>
<body>
<table>
    <tbody>
    <tr>
{{--        <td style="border: 2px solid black;background: #659eca;color: white">ESTADO</td>--}}
        <td style="border: 2px solid black;background: #659eca;color: white">ORDEN</td>
        <td style="border: 2px solid black;background: #659eca;color: white">FECHA MUESTRA</td>
        <td style="border: 2px solid black;background: #659eca;color: white">PROFESIONAL</td>
        <td style="border: 2px solid black;background: #659eca;color: white">APELLIDOS, NOMBRES</td>
        <td style="border: 2px solid black;background: #659eca;color: white">EDAD</td>
    </tr>
   @foreach($resultados as $r)
       <tr>
{{--           <td>Iniciado</td>--}}
           <td>{{$r->ticket}}</td>
           <td>{{!empty($r->fecha) ? now()->parse($r->fecha)->format("d/m/Y") : "" }}</td>
           <td>{{$r->profesional}}</td>
           <td>{{$r->apenom}}</td>
           <td>{{$r->edad}}</td>
       </tr>
   @endforeach
    </tbody>
</table>
</body>
</html>
