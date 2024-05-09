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
        @if(!empty($empresa) && !empty($empresa->imagen_cabecera) && $tipoImagen == "tulogo")
            <img src="{{$empresa->imagen_cabecera_url}}" style="width: 100%;height: 90px" alt="">
        @else
            @if($cabecera == 1)
                <img src="{{asset('img/hoja-membretada-01_01.png')}}" style="width: 100%;height: 96px;visibility: hidden" alt="">
            @endif
            <hr style="color: #49B8E5;visibility: hidden">
        @endif


    </div>
    <div style="width: 100%">
        <div style="border-radius: 10px;border:2px solid black;padding: 0;font-size: 14px;">
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

    <div style="width: 100%;margin-top: 10px;">
        <table border="0" cellpadding="0" style="width: 100%;border-collapse: collapse;">
            <tbody>
            <tr style="font-size: 14px">
                <td style="border: 2px solid black;border-right: 0;padding: 3px"><b>ANALISIS</b></td>
                <td style="border: 2px solid black;border-right: 0;border-left: 0;padding: 3px"><b>RESULTADO</b></td>
                <td style="border: 2px solid black;border-right: 0;border-left: 0;padding: 3px"><b>UNIDAD</b></td>
                <td style="border: 2px solid black;border-left: 0;padding: 3px"><b>VALORES DE REFERENCIA</b></td>
            </tr>
            <tr>
                <td colspan="4" style="padding-top: 3px;padding-bottom: 3px"><b style="font-size: 17px">{{$key}}</b>
                </td>
            </tr>
            @foreach($groupAnalisis as $an)
                @if($an->perfil == $an->nombreexamen && $an->tipo == 4)
                    <tr>
                        <td colspan="4"><b style="font-size: 15px">{{$an->perfil}}</b></td>
                    </tr>
                @elseif(empty(trim($an->resultado)) && empty(trim($an->unidad)) && empty(trim($an->referencia)) && $an->tipo == 3)
                    <tr>
                        <td colspan="4"><b style="font-size: 15px;font-style: italic">{{$an->nombreexamen}}</b></td>
                    </tr>
                @else
                    @if(!empty($an->validadom))
                        <tr style="font-size: 13px">
                            <td style="vertical-align: top;padding: 0" >
                                {{$an->nombreexamen}}
                            </td>
                            @if(empty(trim($an->unidad)) && (!empty(trim($an->referencia)) || (!empty(trim($an->val_min)) || !empty(trim($an->val_max)))))
                                <td style="vertical-align: top;padding: 0" colspan="2">
                            @elseif(empty(trim($an->unidad)) && (empty(trim($an->referencia)) || (empty(trim($an->val_min)) || empty(trim($an->val_max)))))
                                <td style="vertical-align: top;padding: 0" colspan="3">
                            @else
                                <td style="vertical-align: top;padding: 0" >
                                    @endif
                                    @if(!empty(trim($an->resutexto)))
                                        <span style="margin: 0;margin-left: 10px">{{$an->texto}}</span>
                                    @else
                                        <span style="margin: 0;margin-left: 10px">
                                        @if($an->resultado == "I")
                                                Intermedio
                                            @elseif($an->resultado == "R")
                                                Resistente
                                            @elseif($an->resultado == "S")
                                                Sensible
                                            @else
                                                {{$an->resultado}}
                                            @endif
                                    </span>
                                    @endif
                                </td>
                                @if(!empty(trim($an->unidad)) )
                                    <td style="vertical-align: top">
                                        <span style="margin: 0;margin-left: 10px">{{$an->unidad}}</span>
                                    </td>
                                @endif
                                @if(!empty(trim($an->referencia)) || (!empty(trim($an->val_min)) || !empty(trim($an->val_max))))
                                    <td style="vertical-align: top">
                                        @if(!empty($an->val_min) || !empty($an->val_max))
                                            <span style="margin: 0">{{$an->val_min.$an->separador.$an->val_max}}</span>
                                        @endif
                                        @if(!empty($an->referencia))
                                            <span style="margin:0;font-size: 12px;">{!! nl2br($an->referencia) !!}</span>
                                        @endif
                                    </td>
                                @endif
                        </tr>
                        @if(!empty(trim($an->comentario)))
                            <tr>
                                <td colspan="4"><span style="margin:0;margin-left:8px;font-size: 13px;text-transform: uppercase">{{$an->comentario}}</span></td>
                            </tr>
                        @endif
                    @endif
                @endif
            @endforeach
            </tbody>
        </table>
    </div>

    <div style="width: 100%;position: absolute;bottom: 0">

        @if(!empty($empresa) && !empty($empresa->imagen_pie_pagina) && $tipoImagen == "tulogo")
         <div style="text-align: right">
                <img src="{{asset('img/firma_dr_rafael.png')}}" style="width: 200px;height:90px;" alt="">
            </div>
            <img src="{{$empresa->imagen_pie_pagina_url}}" style="width: 100%;" alt="">
        @else
            <div style="text-align: right">
                <img src="{{asset('img/firma_dr_rafael.png')}}" style="width: 200px;height:90px;" alt="">
            </div>
            @if($cabecera == 1)
                <img src="{{asset('img/pie-de-pagina.png')}}" style="width: 100%;" alt="">
            @endif
        @endif
    </div>
    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
</body>
</html>
