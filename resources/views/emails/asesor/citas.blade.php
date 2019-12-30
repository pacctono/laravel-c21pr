@component('mail::message')
# Buenos días, {{ $asesor->name }}!

Por medio del presente te recuerdo tus citas.

{{-- @component('mail::button', ['url' => '']) --}}
{{-- Button Text --}}
{{-- @endcomponent --}}

@component('mail::panel')
    <ul>
{{--    @foreach ($asesor->agendas()->orderBy('fecha_evento')->get() as $cita) --}}
    @foreach ($asesor->citas($desde, $hasta) as $cita)
        <li>{{ $cita->name??'Turno' }} el
        {{ $cita->evento_dia_semana }}
        {{ $cita->evento_con_hora }}.
    @endforeach
    </ul>
@endcomponent

Gracias,<br>
Administración de {{ config('app.name') }}
@endcomponent
