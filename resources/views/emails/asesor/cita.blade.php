@component('mail::message')
# Buenos días, {{ $contacto->user->name }}!

Por medio del presente te recuerdo tu cita.

{{-- @component('mail::button', ['url' => '']) --}}
{{-- Button Text --}}
{{-- @endcomponent --}}

@component('mail::panel')
    {{ $contacto->name }} el
    {{ $contacto->evento_dia_semana }}
    {{ $contacto->evento_con_hora }}.
@endcomponent

Gracias,<br>
Administración de {{ config('app.name') }}
@endcomponent
