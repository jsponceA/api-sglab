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
        <td style="border: 2px solid black;background: #659eca;color: white">ESTADO</td>
        <td style="border: 2px solid black;background: #659eca;color: white">ORDEN</td>
        <td style="border: 2px solid black;background: #659eca;color: white">FECHA MUESTRA</td>
        <td style="border: 2px solid black;background: #659eca;color: white">PROFESIONAL</td>
        <td style="border: 2px solid black;background: #659eca;color: white">APELLIDOS, NOMBRES</td>
        <td style="border: 2px solid black;background: #659eca;color: white">EDAD</td>
    </tr>
   @foreach($resultados as $r)
       <tr>
           <td>
               @if($r->estado == 1)
                   Iniciando
               @elseif($r->estado == 2)
                   En proceso
               @elseif($r->estado == 3)
                   Finalizado
               @else
                   -
               @endif
           </td>
           <td>{{$r->ticket}}</td>
           <td>{{!empty($r->fecha) ? now()->parse($r->fecha)->format("d/m/Y") : "" }}</td>
           <td>{{$r->profesional}}</td>
           <td>{{$r->apenom}}</td>
           <td>
               {{$r->edad}}
               @if($r->medida == 1)
                   Dias
               @elseif($r->medida == 2)
                   Años
               @elseif($r->medida == 3)
                   Meses
               @endif
           </td>
       </tr>
   @endforeach
    </tbody>
</table>
</body>
</html>
