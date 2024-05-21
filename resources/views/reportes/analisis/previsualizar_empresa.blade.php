<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PREVISUALIZAR</title>
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

<div style="width: 100%">
    <p style="margin: 0;text-align: right;font-size: 13px">Pag. 1/1</p>
    <img src="{{$empresa->imagen_cabecera_url}}" style="width: 100%;height: 90px" alt="">
    <hr style="color: #49B8E5;visibility: hidden">

</div>

<div style="width: 100%;position: absolute;bottom: 0">
    <img src="{{$empresa->imagen_pie_pagina_url}}" style="width: 100%;" alt="">
</div>
</body>
</html>
