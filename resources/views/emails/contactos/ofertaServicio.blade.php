@component('mail::message')
# Buenos días, {{ $contacto->name }}!
Atendido por su asesor: {{ $user->name }}

A continuación le envío nuestra Oferta de Servicios:

@component('mail::panel')
    Oferta de Servicio
@endcomponent

@component('mail::button',
            ['url' => 'https://www.century21.com.ve/@puenterealbienesraices'])
Su oficina Puente Real C21
@endcomponent

Sinceramente,<br>
Administración de {{ config('app.name') }}
@endcomponent
