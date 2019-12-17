@component('mail::message')
# Buenos días!

A continuación la información de cierre de la propiedad ({{ $propiedad->codigo }}) {{ $propiedad->nombre }}

@component('mail::table')
|      |            |
| ----:|:---------- |
| **NEGOCIACIÓN:** | ***{{ $propiedad->negociacion_alfa }}*** |
| **CODIGO MLS:** | ***{{ $propiedad->codigo }}*** |
| **INMUEBLE:** | ***{{ $propiedad->nombre }}*** |
| **LADO DEL NEGOCIO:** | ***{{ (1<$propiedad->asesor_captador_id)?'CAPTADOR':'' }} {{ (1<$propiedad->asesor_cerrador_id)?'CERRADOR':'' }}*** |
| **PRECIO DE VENTA:** | ***{{ $propiedad->precio_ven }}*** |
| **MONTO DE RESERVA:** | ***{{ $propiedad->reserva_con_iva_ven }}*** |
| **COMISIÓN APLICADA:** | ***{{ $propiedad->comision_p }}	= {{ $propiedad->compartido_con_iva_ven }}*** |
| **IMPUESTO IVA:** | ***{{ $propiedad->iva_p }} = {{ $propiedad->monto_iva_ven }}*** |
| **FECHA DE RESERVA:** | ***{{ $propiedad->fec_res }}*** |
| **EFECTIVO RECIBIDO POR:** | |
| **FECHA DE FIRMA DOC. VENTA:** | ***{{ $propiedad->fec_fir }}*** |
| **OFERTANTE - COMPRADOR:** | |
| **PROPIETARIO - VENDEDOR:** | ***{{ $propiedad->cliente->name }}*** |
| | ***{{ $propiedad->cliente->cedula }}*** |
| | ***{{ $propiedad->ciudad->descripcion }}*** |
| **ASESORES INVOLUCRADOS:** | |
| **CAPTADOR:** | ***{{ (1==$propiedad->asesor_captador_id)?$propiedad->asesor_captador:$propiedad->captador->name }}*** |
| **CERRADOR:** | ***{{ (1==$propiedad->asesor_cerrador_id)?$propiedad->asesor_cerrador:$propiedad->cerrador->name }}*** |
| **COMENTARIOS ADICIONALES:** | ***{{ $propiedad->comentarios }}*** |
@endcomponent

Administración de {{ config('app.name') }}
@endcomponent
