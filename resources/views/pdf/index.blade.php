<!DOCTYPE html>
<html>
<head>
    <title>PDF</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h3>GENERAR PDF CON LARAVEL</h3>
        <ul>
            <li>
                <a target="_blank" href="{{ action('PdfController@getGenerar',['accion'=>'ver','tipo'=>'digital']) }}">Ver PDF digital</a>
            </li>
            <li>
                <a target="_blank" href="{{ action('PdfController@getGenerar',['accion'=>'ver','tipo'=>'fisico']) }}">Ver PDF físico</a>
            </li>
            <li>
                <a target="_blank" href="{{ action('PdfController@getGenerar',['accion'=>'descargar','tipo'=>'digital']) }}">Descargar PDF digital</a>
            </li>
            <li>
                <a target="_blank" href="{{ action('PdfController@getGenerar',['accion'=>'descargar','tipo'=>'fisico']) }}">Descargar PDF físico</a>
            </li>
        </ul>
    </div>
</body>
</html>