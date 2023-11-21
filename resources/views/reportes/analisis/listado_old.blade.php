<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>RESULTADO DE ANALISIS - {{$ticket}}</title>
    <style>
        * {
            font-family: Arial, serif;
            margin-top: 5px !important;
            margin-bottom: 5px !important;

        }

        body {

        }

        .page-break {
            page-break-after: always;
        }

    </style>
</head>
<body>
@foreach($analisis as $key => $groupAnalisis)
    <div style="width: 100%">
        <p style="margin: 0;text-align: right;font-size: 13px">Pag. {{$loop->iteration}}/{{$loop->count}}</p>
        @if($tipoUsuario != "empresa")
            <img src="{{asset('img/hoja-membretada-01_01.png')}}" style="width: 100%;height: 96px" alt="">
            <hr style="color: #49B8E5;">
        @elseif($tipoUsuario == "empresa" && $cabecera == 1)
            <img src="{{asset('img/hoja-membretada-01_01.png')}}" style="width: 100%;height: 96px" alt="">
            <hr style="color: #49B8E5;">
        @endif
    </div>
    <div style="width: 100%">
        <div style="border-radius: 10px;border:2px solid black;padding: 10px;font-size: 14px;">
            <table border="0" cellpadding="1" style="width: 100%">
                <tbody>
                <tr>
                    <td style="text-align: right">PACIENTE:</td>
                    <td><b style="text-transform: uppercase">{{$resultado->apenom}}</b></td>
                    <td style="text-align: right">FECHA:</td>
                    <td><b>{{!empty($resultado->fecha) ? now()->parse($resultado->fecha)->format("d/m/Y") : ""}}</b>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right">MEDICO:</td>
                    <td><b>{{$resultado->profesional}}</b></td>
                    <td style="text-align: right">NUMERO DE ORDEN:</td>
                    <td><b>{{$resultado->ticket}}</b></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td style="text-align: right">EDAD:</td>
                    <td><b>{{$resultado->edad}} AÑOS</b></td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>
    <div style="width: 100%;margin-top: 10px">
        <table border="0" cellpadding="0" style="width: 100%;border: 2px solid black;border-collapse: collapse;">
            <tbody>
            <tr style="font-size: 14px">
                <td width="280px" style="padding: 5px"><b>ANALISIS</b></td>
                <td style="padding: 5px"><b>RESULTADO</b></td>
                <td style="padding: 5px"><b>UNIDAD</b></td>
                <td style="text-align: center;padding: 5px"><b>VALORES DE REFERENCIA</b></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div style="width: 100%;margin-top: 5px;">
        <table border="0" cellpadding="0" style="width: 100%;">
            <tbody>
            <tr>
                <td colspan="4"><b style="font-size: 19px">{{$key}}</b></td>
            </tr>
            @foreach($groupAnalisis as $an)
                <tr style="font-size: 14px">
                    <td width="270px">
                        @if($an->validadom == 0)
                            <b><i>{{$an->nombreexamen}}</i></b>
                        @else
                            {{$an->nombreexamen}}
                        @endif
                    </td>
                    <td width="100px" style="text-align: center">{{$an->resultado}}</td>
                    <td width="90px" style="text-align: center">{{$an->unidad}}</td>
                    <td>
                        <p style="margin: 0"{{$an->val_min.$an->resultado.$an->val_max}}></p>
                        @if(!empty($an->referencia))
                            <p style="margin: 10px 0 0;font-size: 13px">{!! nl2br($an->referencia) !!}</p>
                        @endif
                    </td>
                </tr>

            @endforeach

            </tbody>
        </table>
    </div>

    <div style="width: 100%;position: absolute;bottom: 0">
        <div style="text-align: right">
            <img src="{{asset('img/firma_dr_rafael.png')}}" style="width: 260px;height: 103px;" alt="">
        </div>
        @if($tipoUsuario != "empresa")
            <hr style="color: #49B8E5">
            <img src="{{asset('img/hoja-membretada-01_07.png')}}" style="width: 100%;" alt="">
        @elseif($tipoUsuario == "empresa" && $cabecera == 1)
            <hr style="color: #49B8E5">
            <img src="{{asset('img/hoja-membretada-01_07.png')}}" style="width: 100%;" alt="">
        @endif
    </div>
    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
</body>
</html>
