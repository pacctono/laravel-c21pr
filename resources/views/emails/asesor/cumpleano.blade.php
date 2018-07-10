@component('mail::message')
# Buenos días, {{ $asesor->name }}!

@component('mail::panel')
    El presente es con la finalidad de desearte un muy Feliz Cumpleaño!
@endcomponent

Sinceramente,<br>
Administración de {{ config('app.name') }}
@endcomponent
