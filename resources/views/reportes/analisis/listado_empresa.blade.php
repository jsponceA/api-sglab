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
        <img src="{{asset('img/hoja-membretada-01_01.png')}}" style="width: 100%;height: 96px;visibility: hidden" alt="">
        <hr style="color: #49B8E5;visibility: hidden">

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
                    <td></td>
                    <td></td>
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
                @if($an->perfil == $an->nombreexamen)
                    <tr>
                        <td colspan="4"><b style="font-size: 15px">{{$an->perfil}}</b></td>
                    </tr>
                @else
                    @if(!empty($an->validadom))
                        <tr style="font-size: 13px">
                            <td style="vertical-align: top">
                                {{$an->nombreexamen}}
                            </td>
                            <td style="vertical-align: top" >
                                @if($an->resutexto)
                                    <p style="margin: 0;margin-left: 10px">{{$an->texto}}</p>
                                @else
                                    <p style="margin: 0;margin-left: 10px">{{$an->resultado}}</p>
                                @endif
                            </td>
                            <td style="vertical-align: top" >
                                <p style="margin: 0;margin-left: 10px">{{$an->unidad}}</p>
                            </td>
                            <td style="vertical-align: top">
                                {{--<p style="margin: 0;margin-left: 10px">{{$an->val_min.$an->resultado.$an->val_max}}</p>--}}
                                @if(!empty($an->referencia))
                                    <p style="margin:0;margin-left: 10px;font-size: 10px;">{!! nl2br($an->referencia) !!}</p>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endif
            @endforeach

            </tbody>
        </table>
    </div>


    <div style="width: 100%;position: absolute;bottom: 0">
        <div style="text-align: right">
            <img src="{{asset('img/firma_dr_rafael.png')}}" style="width: 200px;height: 103px" alt="">
        </div>
        <hr style="color: #49B8E5;visibility: hidden ">
        <img src="{{asset('img/hoja-membretada-01_07.png')}}" style="width: 100%;visibility: hidden" alt="">
    </div>
    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
</body>
</html>
