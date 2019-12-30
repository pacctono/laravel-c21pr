@component('mail::message')
# Buenos días, {{ $modelo->user->name }}!

Por medio del presente te recuerdo tu cita.

{{-- @component('mail::button', ['url' => '']) --}}
{{-- Button Text --}}
{{-- @endcomponent --}}

@component('mail::panel')
    {{ $modelo->name }} el
    {{ $modelo->evento_dia_semana }}
    {{ $modelo->evento_con_hora }}.
@endcomponent

Gracias,<br>
Administración de {{ config('app.name') }}
@endcomponent
