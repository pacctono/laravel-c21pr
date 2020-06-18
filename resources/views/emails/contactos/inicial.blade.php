@component('mail::message')
# Buenos días!

Esta información fue enviada a través de la página web de bienvenida (welcome) por un contacto que se conectó.

@component('mail::table')
|      |            |
| ----:|:---------- |
| **NEGOCIACIÓN:** | ***{{ $datos['negociacion'] }}*** |
| **CIUDAD:** | ***{{ $datos['ciudad'] }}*** |
| **NOMBRE:** | ***{{ $datos['nombre'] }}*** |
| **TIPO:** | ***{{ $datos['tipo'] }}*** |
| **TELEFONO:** | ***{{ $datos['telefono'] }}*** |
@endcomponent

Gracias,<br>
Administración de {{ config('app.name') }}
@endcomponent
