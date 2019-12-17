@component('mail::message')
# Buenos días, Sr(a). Gerente

A continuación podrá observar los incumplimientos de los turnos de la semana pasada.

@component('mail::table')
| Dia  | Turno      | Asesor     | Observacion |
|:---- |:---------- |:---------- |:----------- |
@foreach ($turnos as $turno)
| {{ $turno->turno_dia_semana }} | {{ $turno->fec_tur }} | {{ $turno->user->name }} | {{ $turno->observacion }} |
@endforeach
@endcomponent

Para su revisión,<br>
{{ config('app.name') }}
@endcomponent
