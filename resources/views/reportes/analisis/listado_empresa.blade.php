<!doctype html>
@php
    // La cabecera se repite en cada hoja: se reserva su alto en el margen superior de la pagina
    $margenSuperior = !empty($empresa) && !empty($empresa->imagen_cabecera) && $tipoImagen == "tulogo" ? "7.0cm" : "6.5cm";
    // Alto util (pt) para resultados en una hoja: A4 menos margen superior e inferior (5.8cm)
    $alturaUtil = 841.89 - (floatval($margenSuperior) + 5.8) * 28.3465;
@endphp
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>RESULTADO DE ANALISIS - {{$ticket}}</title>
    <style>
        body, body * {
            font-family: Helvetica, sans-serif;
            margin-top: 5px !important;
            margin-bottom: 5px !important;

        }

        body {

        }

        .page-break {
            page-break-after: always;
        }

        /* Se reserva el espacio inferior para el pie de pagina para que el contenido no quede debajo de la firma */
        @page {
            margin: {{$margenSuperior}} 1.2cm 5.8cm 1.2cm;
        }

        .cabecera-pagina {
            position: fixed;
            left: 0;
            right: 0;
            top: -{{$margenSuperior}};
        }

        .pie-pagina {
            position: fixed;
            left: 0;
            right: 0;
            bottom: -4.6cm;
        }

    </style>
</head>
<body>
<div class="pie-pagina">
    @if(!empty($empresa) && !empty($empresa->imagen_pie_pagina) && $tipoImagen == "tulogo")
        <div style="text-align: right">
            <img src="{{public_path('img/firma_dr_rafael.png')}}" style="width: 200px;height:90px;" alt="">
        </div>
        <img src="{{$empresa->imagenPiePaginaPdf()}}" style="width: 100%;" alt="">
    @else
        <div style="text-align: right">
            <img src="{{public_path('img/firma_dr_rafael.png')}}" style="width: 200px;height:90px;" alt="">
        </div>
        @if($cabecera == 1)
            <img src="{{public_path('img/pie-de-pagina.png')}}" style="width: 100%;" alt="">
        @else
            <img src="{{public_path('img/pie-de-pagina.png')}}" style="width: 100%;visibility: hidden" alt="">
        @endif
    @endif
</div>
<div class="cabecera-pagina">
    <div style="width: 100%">
        <p style="margin: 0;text-align: right;font-size: 13px">&nbsp;</p>
        @if(!empty($empresa) && !empty($empresa->imagen_cabecera) && $tipoImagen == "tulogo")
            <img src="{{$empresa->imagenCabeceraPdf()}}" style="width: 100%;height: 110px" alt="">
        @else
            @if($cabecera == 1)
                <img src="{{public_path('img/cabecera-de-hoja.png')}}" style="width: 100%;height: 90px" alt="">
            @else
                <img src="{{public_path('img/cabecera-de-hoja.png')}}" style="width: 100%;height: 90px;visibility: hidden" alt="">
            @endif
        @endif


    </div>
    <div style="width: 100%">
        <div style="border-radius: 10px;border:1px solid black;padding: 0;font-size: 12px;">
            <table border="0" cellpadding="1" style="width: 100%">
                <tbody>
                <tr>
                    <td style="text-align: right">PACIENTE :</td>
                    <td><b style="text-transform: uppercase">{{$resultado->apenom}}</b></td>
                    <td style="text-align: right">FECHA :</td>
                    <td><b>{{!empty($resultado->fecha) ? now()->parse($resultado->fecha)->format("d/m/Y") : ""}}</b>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td style="text-align: right">NUMERO DE ORDEN :</td>
                    <td><b>{{strlen($resultado->ticket) > 8 ? substr($resultado->ticket, 2) : $resultado->ticket}}</b></td>
                </tr>
                <tr>
                    @if(!empty($empresa) && $empresa->mostrar_referencia)
                        <td style="text-align: right">REFERENCIA :</td>
                        <td><b>{{$usuario}}</b></td>
                    @else
                        <td></td>
                        <td></td>
                    @endif
                    <td style="text-align: right">EDAD :</td>
                    <td><b>{{$resultado->edad}}
                            @if($resultado->medida == 1)
                                DIAS
                            @elseif($resultado->medida == 2)
                                AÑOS
                            @elseif($resultado->medida == 3)
                                MESES
                            @endif
                        </b></td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>
    <div style="width: 100%;margin-top: 10px;">
        <table border="0" cellpadding="0" style="width: 100%;border-collapse: collapse;table-layout: fixed;">
            <tbody>
            <tr style="height: 0;line-height: 0;font-size: 0"><td style="width: 39%;padding: 0;height: 0;line-height: 0;font-size: 0"></td><td style="width: 15%;padding: 0;height: 0;line-height: 0;font-size: 0"></td><td style="width: 12%;padding: 0;height: 0;line-height: 0;font-size: 0"></td><td style="width: 34%;padding: 0;height: 0;line-height: 0;font-size: 0"></td></tr>
            <tr style="font-size: 12px">
                <td style="border: 1px solid black;border-right: 0;padding: 3px"><b>ANALISIS</b></td>
                <td style="border: 1px solid black;border-right: 0;border-left: 0;padding: 3px"><b>RESULTADO</b></td>
                <td style="border: 1px solid black;border-right: 0;border-left: 0;padding: 3px"><b>UNIDAD</b></td>
                <td style="border: 1px solid black;border-left: 0;padding: 3px"><b>VALORES DE REFERENCIA</b></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
@foreach($analisis as $key => $groupAnalisis)
            @php
                // Cada perfil (titulo + sus analisis) va en un bloque que no se corta entre hojas
                $bloques = \App\Support\BloquesResultado::agrupar($groupAnalisis, $alturaUtil);
            @endphp
            @foreach($bloques as $bloque)
        <table border="0" cellpadding="0" style="width: 100%;border-collapse: collapse;table-layout: fixed;margin-top: 0 !important;margin-bottom: 0 !important;@if($bloque["perfil"]) page-break-inside: avoid; @endif">
            <tbody>
            <tr style="height: 0;line-height: 0;font-size: 0"><td style="width: 39%;padding: 0;height: 0;line-height: 0;font-size: 0"></td><td style="width: 15%;padding: 0;height: 0;line-height: 0;font-size: 0"></td><td style="width: 12%;padding: 0;height: 0;line-height: 0;font-size: 0"></td><td style="width: 34%;padding: 0;height: 0;line-height: 0;font-size: 0"></td></tr>
            @if($loop->first)
            <tr>
                <td colspan="4" style="padding-top: 3px;padding-bottom: 3px"><b style="font-size: 17px">{{$key}}</b>
                </td>
            </tr>
            @endif
            @foreach($bloque["items"] as $an)
                @if($an->perfil == $an->nombreexamen && $an->tipo == 4)
                    <tr>
                        <td colspan="4"><b style="font-size: 15px">{{$an->perfil}}</b></td>
                    </tr>
                @elseif(empty(trim($an->resultado)) && empty(trim($an->unidad)) && empty(trim($an->referencia)) && $an->tipo == 3)
                    <tr>
                        <td colspan="4"><b style="font-size: 13px;font-style: italic">{{$an->nombreexamen}}</b></td>
                    </tr>
                @else
                    @if(!empty($an->validadom))
                        <tr style="font-size: 13px;line-height: 20px">
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
                                            <span style="margin:0;font-size: 12px;display: inline-block; line-height:1;">{!! nl2br(htmlspecialchars($an->referencia, ENT_QUOTES, 'UTF-8')) !!}</span>
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
            @endforeach

    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
</body>
</html>
