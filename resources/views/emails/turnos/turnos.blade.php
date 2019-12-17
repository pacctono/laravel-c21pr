@component('mail::message')
# Buenos días, {{ $asesor->name }}!

Por medio del presente te recuerdo tus turnos.

{{-- @component('mail::button', ['url' => '']) --}}
{{-- Button Text --}}
{{-- @endcomponent --}}

@component('mail::panel')
    <ul>
    @foreach ($turnos as $turno)
    <li>El {{ $turno->turno_dia_semana }}
    {{ $turno->turno_con_hora }}.
    @endforeach
    </ul>
@endcomponent

Gracias,<br>
Administración de {{ config('app.name') }}
@endcomponent
