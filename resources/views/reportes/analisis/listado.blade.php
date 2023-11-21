<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>RESULTADO DE ANALISIS - {{$ticket}}</title>
    <style>
        @font-face {
            font-family: 'Arial MT';
            src: url('{{ public_path("fonts/arialmt.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        * {
            font-family: 'Arial MT', serif;
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
        <img src="{{asset('img/hoja-membretada-01_01.png')}}" style="width: 100%;height: 96px" alt="">
        <hr style="color: #49B8E5;">
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
                    @if($tipoUsuario == "empresa")
                        <td style="text-align: right">REFERENCIA:</td>
                        <td><b>{{$usuario}}</b></td>
                    @else
                        <td></td>
                        <td></td>
                    @endif
                    <td style="text-align: right">EDAD:</td>
                    <td><b>{{$resultado->edad}} AÑOS</b></td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>
    <div style="width: 100%;margin-top: 10px">
        <table border="0" cellpadding="0" style="width: 100%;border-collapse: collapse;">

            <tbody>
            <tr style="font-size: 14px">
                <td style="border: 2px solid black;border-right: 0;padding: 3px"><b>ANALISIS</b></td>
                <td style="border: 2px solid black;border-right: 0;border-left: 0;padding: 3px"><b>RESULTADO</b></td>
                <td style="border: 2px solid black;border-right: 0;border-left: 0;padding: 3px"><b>UNIDAD</b></td>
                <td style="border: 2px solid black;border-left: 0;padding: 3px"><b>VALORES DE REFERENCIA</b></td>
            </tr>
            <tr>
                <td colspan="4" style="padding-top: 5px;padding-bottom: 5px"><b style="font-size: 17px">{{$key}}</b>
                </td>
            </tr>
            @foreach($groupAnalisis as $an)
                <tr style="font-size: 13px">
                    <td width="270px">
                        @if($an->validadom == 0)
                            <b><i>{{$an->nombreexamen}}</i></b>
                        @else
                            {{$an->nombreexamen}}
                        @endif
                    </td>
                    <td width="100px">
                        <p style="margin: 0;margin-left: 10px">{{$an->resultado}}</p>
                    </td>
                    <td width="90px">
                        <p style="margin: 0;margin-left: 10px">{{$an->unidad}}</p>
                    </td>
                    <td>
                        <p style="margin: 0;margin-left: 10px"{{$an->val_min.$an->resultado.$an->val_max}}></p>
                        @if(!empty($an->referencia))
                            <p style="margin:0;margin-left: 10px;font-size: 10px">{!! nl2br($an->referencia) !!}</p>
                        @endif
                    </td>
                </tr>

            @endforeach

            </tbody>
        </table>
    </div>


    <div style="width: 100%;position: absolute;bottom: 0">
        <div style="text-align: right">
            <img src="{{asset('img/firma_dr_rafael.png')}}" style="width: 200px;height: 103px;" alt="">
        </div>
        <hr style="color: #49B8E5">
        <img src="{{asset('img/hoja-membretada-01_07.png')}}" style="width: 100%;" alt="">
    </div>
    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
</body>
</html>
