@component('mail::message')
# Buenos días, {{ $contacto->name }}!
Atendido por su asesor: {{ $user->name }}

Atención: Apreciado cliente: 

A continuación, le informamos sobre los servicios inmobiliarios para la comercialización de inmuebles:

@component('mail::panel')
El  sistema  CENTURY  21  está  reconocido  como  la  Red  Inmobiliaria  más  grande  y  exitosa  de Venezuela y el mundo.   Como parte de ese exitoso sistema, nuestra oficina CENTURY 21 Puente Real Bienes Raíces, incorpora la reputación basada en la honestidad, transparencia, confianza y trato personal, a un esquema de alta eficiencia en el manejo de compra, venta y arrendamiento de bienes inmuebles.

En estas condiciones, nuestro servicio supera ampliamente la atención habitualmente ofrecida al asesorarle en cada etapa de la operación, como se muestra a continuación:

ETAPAS DEL PROCESO DE COMERCIALIZACION


1. Previo al inicio de promoción:

A) Análisis Legal: Verificamos  que  toda la  documentación  del  inmueble  esté  en  orden  y  que  no haya trámites  que completar para evitar contratiempos que compliquen la venta.
B) Análisis Fiscal: Verificamos el efecto fiscal derivado de la operación de venta y le informamos el monto aproximado a pagar por concepto de I.S.L.R., inclusive si usted está en posibilidades de exentar dicho impuesto.
C) Análisis Comercial: Realizamos un estudio de valor comercial basado en la Ley de la Oferta y la Demanda para determinar con toda precisión el precio óptimo sugerible de venta o alquiler de su propiedad.

D) Plan de Mercadeo: Nuestro plan de mercadeo consiste en evaluar el mercado comprador para así promocionar su inmueble de la forma más precisa y concretar la venta rápida del mismo, según lo amerite publicamos en prensa regional y en todos los casos nuestras publicaciones fijas con, posicionamiento destacado, son por nuestra página web: www.century21.com.ve  y   https://www.century21.com.ve/@puenterealbienesraices  además de los portales:  
www.ConLallave.com www.TuInmueble.com www.MercadoLibre.com.ve, www.Inmobilia.com  www.bienesonline.com  www.Trovit.com.ve     www.Babanuncios.com   www.Ve.Vendebien.com. 

Y en nuestras redes sociales: Instagram: @C21puentereal 

•	Ofrecemos una red de más de ciento veinte (120) oficinas y más de 1.800 asesores en todo el país.
•	Colocamos el prestigioso rotulo de CENTURY21 para Venta o Alquiler.
•	Le asesoramos durante todo el proceso de negociación de Venta o Alquiler, desde el principio hasta el final de la operación.
•	Sistema de referidos de compra-venta y alquiler nacional e internacional que permite la captación y reubicación de clientes foráneos.

2. Etapa de promoción:

El Sistema CENTURY 21, tiene una imagen muy poderosa a nivel nacional e internacional que permite a las oficinas afiliadas captar a un mayor número de clientes potenciales, algunos medios de promoción son los siguientes:

a) A Nivel Local:
•	Publicación diario principal de la región (opcional)
•	Colocación del letrero CENTURY 21 de Venta o Alquiler.
•	Manejo de amplia cartera de clientes.

b) A Nivel Sistema:
•	Promoción  institucional  para  la  difusión  de  la  marca  CENTURY  21  a  través  de  medios masivos.
•	Información  de  su  propiedad  a  las  demás  oficinas  afiliadas  a  través  de  la    página    de
CENTURY 21 (http://www.century21venezuela.com).
•	Manejo de solicitudes vía correo electrónico.
•	Sistema de referidos de compra y venta nacional e internacional que permite la captación y reubicación de clientes foráneos.

3. Atención a prospectos:
Contamos con personal de tiempo completo, debidamente entrenado para proporcionar información y hacer una búsqueda y demostración profesional de inmuebles.



4. Seguimiento de negociaciones:
Usted contará con todo nuestro esfuerzo y capacidad profesional para obtener del cliente la mejor propuesta de compra y/o arrendamiento por escrito.

5. Cierre de la operación:
Una vez que la propuesta de compra y/o arrendamiento sea aceptada y negociados todos los detalles de la operación, elaboraremos el respectivo contrato de Opción de Compra Venta o Arrendamiento, el cual tendrá un costo adicional que será cancelado por el comprador o arrendatario. En caso de una operación de arrendamiento también apoyaremos el trámite de la fianza respectiva o la investigación del inquilino y su fiador.

6. Supervisión Registral:
Coordinamos y damos seguimiento al trámite registral agilizando las gestiones necesarias para la protocolización de la compra venta correspondiente.

7. Vigencia de los servicios:
Nuestro contrato de Prestación de Servicios tiene una vigencia de tres meses (90 días continuos), tiempo durante el cual nuestra oficina CENTURY 21 Puente Real Bienes Raíces, tendrá con carácter de exclusiva la promoción del inmueble, a fin de garantizarle un servicio profesional.

8.Gastos:
Todos los gastos de carácter promocional y publicitario serán cubiertos por  nuestra oficina  durante la vigencia del contrato de Prestación de Servicios en Exclusiva.

9. Honorarios:
•	Para el caso de venta, los honorarios que devengarán nuestra oficina, será equivalente al 5%
más IVA, calculado sobre el precio final de cierre de la operación de compraventa.
•	Para el caso de arrendamiento, nuestros honorarios profesionales, serán el equivalente a un mes de Alquiler más el IVA.

Esperamos que nos considere como la mejor opción para manejar profesionalmente la Venta o Alquiler de su propiedad.

Como información general, para la venta definitiva de su inmueble necesitará la siguiente documentación:

- Copia del documento de propiedad.
- Cédula o Ficha Catastral vigente (alcaldía)
- Solvencia Municipal, actualizada al año en curso (Alcaldía) 
- Copia de la Cédula de Identidad del vendedor o vendedores
- Copia del RIF del vendedor o vendedores
- Solvencias de Condominio e Hidrocaribe (opcional)
- Cancelación de la Forma 33 (Seniat) o Registro de Vivienda Principal.
- Si su venta está prevista realizarse a través de un poder, necesitará C.I y RIF del apoderado, así como el poder debidamente registrado.

@endcomponent

@component('mail::button',
            ['url' => 'https://www.century21.com.ve/@puenterealbienesraices'])
Su oficina Puente Real C21
@endcomponent

Gracias por solicitar nuestros servicios.<br>
Gerencia {{ config('app.name') }}
@endcomponent
