@component('mail::message')
# Buenos días, {{ $asesor->name }}!

Por medio del presente te recuerdo tus citas.

{{-- @component('mail::button', ['url' => '']) --}}
{{-- Button Text --}}
{{-- @endcomponent --}}

@component('mail::panel')
    <ul>
    @foreach ($asesor->contactos()->orderBy('fecha_evento')->get() as $contacto)
        <li>{{ $contacto->name }} el
        {{ $contacto->evento_dia_semana }}
        {{ $contacto->evento_con_hora }}.
    @endforeach
    </ul>
@endcomponent

Gracias,<br>
Administración de {{ config('app.name') }}
@endcomponent
