<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home/{alertar?}', 'HomeController@index')
    ->where('alertar', '(-[1-3])|[0-1]')       // Permite -3, -2, -1, -0, 0, 1, 2, 3
    ->name('home');

Route::get('/usuarios', 'UserController@index')
    ->name('users');

Route::get('/usuarios/orden/{orden}/accion/{accion?}', 'UserController@index')
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('users.orden');

Route::get('/usuarios/{user}', 'UserController@show')
    ->where('user', '[0-9]+')
    ->name('users.show');

Route::get('/usuarios/nuevo', 'UserController@create')
    ->name('users.create')
    ->middleware('admin');

Route::post('/usuarios', 'UserController@store');

Route::get('/usuarios/{user}/editar', 'UserController@edit')
    ->where('user', '[0-9]+')
    ->name('users.edit');

Route::get('/usuarios/{user}/desActivar', 'UserController@updateActivo')
    ->where('user', '[0-9]+')
    ->name('users.updateActivo');

Route::put('/usuarios/{user}', 'UserController@update');

Route::delete('/usuarios/{user}', 'UserController@destroy')
    ->name('users.destroy')
    ->middleware('admin');

Route::get('/contactos/orden/{orden}/accion/{accion?}', 'ContactoController@index')
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('contactos.orden');

Route::get('/contactos/filtro', 'ContactoController@index');     // Para paginación con filtro.
Route::post('/contactos/filtro', 'ContactoController@index')
    ->name('contactos.post');

Route::pattern('contactos', '[0-9]+');  // Para no crear conflictos con el resource contacto
/* Las rutas de resource estan traducidos (crear y editar) en el metodo 'boot'
 * de "app/Providers/AppServiceProvider.php".*/
Route::resource('contactos', 'ContactoController');

Route::get('/contactos/{contacto}/{rutRetorno}', 'ContactoController@show')
    ->where('contacto', '[0-9]+')
    ->name('contactos.muestra');

Route::get('/contactos/correo/{contacto}/{ruta}', 'ContactoController@correoOfertaServicio')
    ->where('contacto', '[0-9]+')
    ->where('ruta', '[0-2]')
    ->name('contacto.correo');

Route::get('/clientes/vClientes/', 'VistaClienteController@vClientes')
    ->name('clientes.vClientes');

Route::get('/turnos', 'TurnoController@index')
    ->name('turnos');

Route::get('/turnos/calendario/{miCalendario?}', 'TurnoController@calendario')
    ->where('miCalendario', '[01]?')
    ->name('turnos.calendario');

Route::post('/turnos/calendario/filtro', 'TurnoController@calendario')
    ->name('calendario.post');

Route::get('/turnos/crear/{semana}', 'TurnoController@crear')
    ->name('turnos.crear');

Route::post('/turnos', 'TurnoController@store');

Route::get('/turnos/{turno}/editar', 'TurnoController@editar')
    ->where('turno', '[0-9]+')
    ->name('turnos.editar');

Route::get('/turnos/editar/{turno}/{asesor}', 'TurnoController@editarTurno')
    ->where('turno', '[0-9]+')
    ->where('asesor', '[0-9]+')
    ->name('turnos.editarTurno');

Route::put('/turnos/{turno}', 'TurnoController@update');

Route::delete('/turnos/{turno}', 'TurnoController@destroy')
    ->name('turnos.destroy');

Route::get('/turnos/orden/{orden}/accion/{accion?}', 'TurnoController@index')
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('turnos.orden');

Route::get('/turnos/filtro', 'TurnoController@index');     // Para paginación con filtro.
Route::post('/turnos/filtro', 'TurnoController@index')
    ->name('turnos.post');

Route::get('/correoTurnos/', 'TurnoController@correoTurnos')
    ->name('turnos.correoTurnos');

Route::get('/clientes/orden/{orden}/accion/{accion?}', 'ClienteController@index')
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('clientes.orden');

Route::get('/clientes/filtro', 'ClienteController@index');     // Para paginación con filtro.
Route::post('/clientes/filtro', 'ClienteController@index')
    ->name('clientes.post');

Route::pattern('clientes', '[0-9]+');               // Para no crear conflictos con el resource cliente
/* Las rutas de resource estan traducidos (crear y editar) en el metodo 'boot'
 * de "app/Providers/AppServiceProvider.php".*/
Route::resource('clientes', 'ClienteController');

Route::get('/agenda', 'AgendaController@index')
    ->name('agenda');

Route::get('/agenda/orden/{orden}/accion/{accion?}', 'AgendaController@index')
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('agenda.orden');

Route::get('/agenda/filtro', 'AgendaController@index');     // Para paginación con filtro.
Route::post('/agenda/filtro', 'AgendaController@index')
    ->name('agenda.post');

Route::get('/agenda/{contacto}', 'AgendaController@show')
    ->where('contacto', '[0-9]+')
    ->name('agenda.show');

Route::get('/agenda/{contacto}/crear', 'AgendaController@create')
    ->name('agenda.crear');

Route::post('/agenda', 'AgendaController@store')
    ->name('agenda.store');

Route::get('/agenda/{contacto}/editar', 'AgendaController@edit')
    ->where('contacto', '[0-9]+')
    ->name('agenda.edit');

Route::put('/agenda/{cita}', 'AgendaController@update')
    ->name('agenda.update');

Route::get('/agendaPersonal/{cita}', 'AgendaPersonalController@show')
    ->where('cita', '[0-9]+')
    ->name('agendaPersonal.show');

Route::get('/agendaPersonal/crear', 'AgendaPersonalController@create')
    ->name('agendaPersonal.crear');

Route::post('/agendaPersonal', 'AgendaPersonalController@store')
    ->name('agendaPersonal.store');

Route::get('/agendaPersonal/{cita}/editar', 'AgendaPersonalController@edit')
    ->where('cita', '[0-9]+')
    ->name('agendaPersonal.edit');

Route::put('/agendaPersonal/{cita}', 'AgendaPersonalController@update')
    ->where('cita', '[0-9]+')
    ->name('agendaPersonal.update');

Route::get('/propiedades/orden/{orden}/accion/{accion?}', 'PropiedadController@index')
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('propiedades.orden');

Route::get('/propiedades/filtro', 'PropiedadController@index');     // Para paginación con filtro.
Route::post('/propiedades/filtro', 'PropiedadController@index')
    ->name('propiedades.post');

Route::get('/propiedades/grabar', 'PropiedadController@grabarArchivo')
    ->name('propiedades.grabar');

Route::get('/propiedades/ajax/', 'PropiedadController@ajPropiedades')
    ->name('ajpropiedades');

Route::get('/propiedades/actCodigo/{propiedad}/{codigo}', 'PropiedadController@updateCodigo')
    ->where('propiedad', '[0-9]+')
    ->where('codigo', '[0-9]+')
    ->name('propiedades.updateCodigo');

Route::pattern('propiedades', '[0-9]+');               // Para no crear conflictos con el resource propiedad
Route::resource('propiedades', 'PropiedadController')
    ->parameters(['propiedades' => 'propiedad']);

Route::get('/propiedades/{propiedad}/{rutRetorno}', 'PropiedadController@show')
    ->where('propiedad', '[0-9]+')
    ->name('propiedades.muestra');

Route::get('/propiedades/correo/{propiedad}/{ruta?}', 'PropiedadController@correoReporteCierre')
    ->where('propiedad', '[0-9]+')
    ->where('ruta', '[012]')
    ->name('propiedad.correo');

Route::get('/avisos/{orden?}/{accion?}', 'AvisoController@index')
// Cualquier nombre que comience con letra, luego letra, numero o '_'; excepto 'nuevo'. Otras palabras, usar '|'.
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('avisos');

Route::get('/avisos/filtro', 'AvisoController@index');     // Para paginación con filtro.
Route::post('/avisos/filtro', 'AvisoController@index')
    ->name('avisos.post');

Route::get('/avisos/nuevo', 'AvisoController@create')
    ->name('avisos.crear')
    ->middleware('admin');

Route::get('/avisos/{aviso}', 'AvisoController@show')
    ->where('aviso', '[0-9]+')
    ->name('avisos.show');

Route::post('/avisos', 'AvisoController@store')
    ->name('avisos.store');

Route::get('/avisos/{aviso}/editar', 'AvisoController@edit')
    ->where('aviso', '[0-9]+')
    ->name('avisos.editar');

Route::put('/avisos/{aviso}/{rutaRetorno?}', 'AvisoController@update')
    ->where('aviso', '[0-9]+')
    ->name('avisos.update');

Route::delete('/avisos/{aviso}', 'AvisoController@destroy')
    ->name('avisos.destroy')
    ->middleware('admin');

Route::get('/reportes/tipo/{tipo}/accion/{accion?}', 'ReporteController@index')
    ->name('reportes');

Route::post('/reportes', 'ReporteController@index')
    ->name('reportes.post');

Route::get('/reportes/chart/{chart}/{accion?}', 'ReporteController@chart')
    ->name('reportes.chart');

Route::post('/reportes/chart/{chart}/{accion?}', 'ReporteController@chart')
    ->name('reportes.chart.post');

Route::get('/reportes/contactosUser/{id}/{orden}', 'ReporteController@contactosXUser')
    ->name('reporte.contactosUser');

Route::get('/reportes/propiedadesUser/{id}/{orden}', 'ReporteController@propiedadesX')
    ->name('reporte.propiedadesUser');

Route::get('/reportes/propiedadesCaracteristica/{id}/{orden}', 'ReporteController@propiedadesX')
    ->name('reporte.propiedadesCaracteristica');

Route::get('/reportes/propiedadesFormaPago/{id}/{orden}', 'ReporteController@propiedadesX')
    ->name('reporte.propiedadesFormaPago');

Route::get('/reportes/contactosDeseo/{id}/{orden}', 'ReporteController@contactosX')
    ->name('reporte.contactosDeseo');

Route::get('/reportes/contactosOrigen/{id}/{orden}', 'ReporteController@contactosX')
    ->name('reporte.contactosOrigen');

Route::get('/reportes/contactosPrice/{id}/{orden}', 'ReporteController@contactosX')
    ->name('reporte.contactosPrice');

Route::get('/reportes/contactosPrecio/{id}/{orden}', 'ReporteController@contactosX')
    ->name('reporte.contactosPrecio');

Route::get('/reportes/contactosResultado/{id}/{orden}', 'ReporteController@contactosX')
    ->name('reporte.contactosResultado');

Route::get('/reportes/propiedadesTipo/{id}/{orden}', 'ReporteController@propiedadesX')
    ->name('reporte.propiedadesTipo');

Route::get('/reportes/contactosTipo/{id}/{orden}', 'ReporteController@contactosX')
    ->name('reporte.contactosTipo');

Route::get('/reportes/propiedadesCiudad/{id}/{orden}', 'ReporteController@propiedadesX')
    ->name('reporte.propiedadesCiudad');

Route::get('/reportes/propiedadesMunicipio/{id}/{orden}', 'ReporteController@propiedadesX')
    ->name('reporte.propiedadesMunicipio');

Route::get('/reportes/propiedadesEstado/{id}/{orden}', 'ReporteController@propiedadesX')
    ->name('reporte.propiedadesEstado');

Route::get('/reportes/contactosZona/{id}/{orden}', 'ReporteController@contactosX')
    ->name('reporte.contactosZona');

Route::get('/caracteristicas/{orden?}/{accion?}', 'CaracteristicaController@index')
// Cualquier nombre que comience con letra, luego letra, numero o '_'; excepto 'nuevo'. Otras palabras, usar '|'.
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('caracteristica');

Route::get('/caracteristicas/nuevo', 'CaracteristicaController@create')
    ->name('caracteristica.crear')
    ->middleware('admin');

Route::get('/caracteristicas/{caracteristica}', 'CaracteristicaController@show')
    ->where('caracteristica', '[0-9]+')
    ->name('caracteristica.show');

Route::post('/caracteristicas', 'CaracteristicaController@store');

Route::get('/caracteristicas/{caracteristica}/editar', 'CaracteristicaController@edit')
    ->where('caracteristica', '[0-9]+')
    ->name('caracteristica.edit');

Route::put('/caracteristicas/{caracteristica}', 'CaracteristicaController@update');

Route::delete('/caracteristicas/{caracteristica}', 'CaracteristicaController@destroy')
    ->name('caracteristica.destroy')
    ->middleware('admin');

Route::get('/deseos/nuevo', 'DeseoController@create')
    ->name('deseo.crear')
    ->middleware('admin');

Route::get('/deseos/{orden?}/{accion?}', 'DeseoController@index')
// Cualquier nombre que comience con letra, luego letra, numero o '_'; excepto 'nuevo'. Otras palabras, usar '|'.
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('deseo');

Route::get('/deseos/{deseo}', 'DeseoController@show')
    ->where('deseo', '[0-9]+')
    ->name('deseo.show');

Route::post('/deseos', 'DeseoController@store');

Route::get('/deseos/{deseo}/editar', 'DeseoController@edit')
    ->where('deseo', '[0-9]+')
    ->name('deseo.edit');

Route::put('/deseos/{deseo}', 'DeseoController@update');

Route::delete('/deseos/{deseo}', 'DeseoController@destroy')
    ->name('deseo.destroy')
    ->middleware('admin');

Route::get('/feriados/{orden?}/{accion?}', 'FeriadoController@index')
// Cualquier nombre que comience con letra, luego letra, numero o '_'; excepto 'nuevo'. Otras palabras, usar '|'.
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('feriado');

Route::get('/feriados/nuevo', 'FeriadoController@create')
    ->name('feriado.crear')
    ->middleware('admin');

Route::get('/feriados/{feriado}', 'FeriadoController@show')
    ->where('feriado', '[0-9]+')
    ->name('feriado.show');

Route::post('/feriados', 'FeriadoController@store');

Route::get('/feriados/{feriado}/editar', 'FeriadoController@edit')
    ->where('feriado', '[0-9]+')
    ->name('feriado.edit');

Route::put('/feriados/{feriado}', 'FeriadoController@update');

Route::delete('/feriados/{feriado}', 'FeriadoController@destroy')
    ->name('feriado.destroy')
    ->middleware('admin');

Route::get('/forma_pagos/{orden?}/{accion?}', 'FormaPagoController@index')
// Cualquier nombre que comience con letra, luego letra, numero o '_'; excepto 'nuevo'. Otras palabras, usar '|'.
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('forma_pago');

Route::get('/forma_pagos/nuevo', 'FormaPagoController@create')
    ->name('forma_pago.crear')
    ->middleware('admin');

Route::get('/forma_pagos/{forma_pago}', 'FormaPagoController@show')
    ->where('forma_pago', '[0-9]+')
    ->name('forma_pago.show');

Route::post('/forma_pagos', 'FormaPagoController@store');

Route::get('/forma_pagos/{forma_pago}/editar', 'FormaPagoController@edit')
    ->where('forma_pago', '[0-9]+')
    ->name('forma_pago.edit');

Route::put('/forma_pagos/{forma_pago}', 'FormaPagoController@update');

Route::delete('/forma_pagos/{forma_pago}', 'FormaPagoController@destroy')
    ->name('forma_pago.destroy')
    ->middleware('admin');

Route::get('/origenes/nuevo', 'OrigenController@create')
    ->name('origen.crear')
    ->middleware('admin');

Route::get('/origenes/{orden?}/{accion?}', 'OrigenController@index')
// Cualquier nombre que comience con letra, luego letra, numero o '_'; excepto 'nuevo'. Otras palabras, usar '|'.
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('origen');

Route::get('/origenes/{origen}', 'OrigenController@show')
    ->where('origen', '[0-9]+')
    ->name('origen.show');

Route::post('/origenes', 'OrigenController@store');

Route::get('/origenes/{origen}/editar', 'OrigenController@edit')
    ->where('origen', '[0-9]+')
    ->name('origen.edit');

Route::put('/origenes/{origen}', 'OrigenController@update');

Route::delete('/origenes/{origen}', 'OrigenController@destroy')
    ->name('origen.destroy')
    ->middleware('admin');

Route::get('/prices/nuevo', 'PriceController@create')
    ->name('price.crear')
    ->middleware('admin');

Route::get('/prices/{orden?}/{accion?}', 'PriceController@index')
// Cualquier nombre que comience con letra, luego letra, numero o '_'; excepto 'nuevo'. Otras palabras, usar '|'.
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('price');

Route::get('/prices/{price}', 'PriceController@show')
    ->where('price', '[0-9]+')
    ->name('price.show');

Route::post('/prices', 'PriceController@store');

Route::get('/prices/{price}/editar', 'PriceController@edit')
    ->where('price', '[0-9]+')
    ->name('price.edit');

Route::put('/prices/{price}', 'PriceController@update');

Route::delete('/prices/{price}', 'PriceController@destroy')
    ->name('price.destroy')
    ->middleware('admin');

Route::get('/precios/nuevo', 'PrecioController@create')
    ->name('precio.crear')
    ->middleware('admin');

Route::get('/precios/{orden?}/{accion?}', 'PrecioController@index')
// Cualquier nombre que comience con letra, luego letra, numero o '_'; excepto 'nuevo'. Otras palabras, usar '|'.
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('precio');

Route::get('/precios/{precio}', 'PrecioController@show')
    ->where('precio', '[0-9]+')
    ->name('precio.show');

Route::post('/precios', 'PrecioController@store');

Route::get('/precios/{precio}/editar', 'PrecioController@edit')
    ->where('precio', '[0-9]+')
    ->name('precio.edit');

Route::put('/precios/{precio}', 'PrecioController@update');

Route::delete('/precios/{precio}', 'PrecioController@destroy')
    ->name('precio.destroy')
    ->middleware('admin');

Route::get('/resultados/nuevo', 'ResultadoController@create')
    ->name('resultado.crear')
    ->middleware('admin');

Route::get('/resultados/{orden?}/{accion?}', 'ResultadoController@index')
// Cualquier nombre que comience con letra, luego letra, numero o '_'; excepto 'nuevo'. Otras palabras, usar '|'.
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('resultado');

Route::get('/resultados/{resultado}', 'ResultadoController@show')
    ->where('resultado', '[0-9]+')
    ->name('resultado.show');

Route::post('/resultados', 'ResultadoController@store');

Route::get('/resultados/{resultado}/editar', 'ResultadoController@edit')
    ->where('resultado', '[0-9]+')
    ->name('resultado.edit');

Route::put('/resultados/{resultado}', 'ResultadoController@update');

Route::delete('/resultados/{resultado}', 'ResultadoController@destroy')
    ->name('resultado.destroy')
    ->middleware('admin');

Route::get('/ciudades/nuevo', 'CiudadController@create')
    ->name('ciudad.crear')
    ->middleware('admin');

Route::get('/ciudades/{orden?}/{accion?}', 'CiudadController@index')
// Cualquier nombre que comience con letra, luego letra, numero o '_'; excepto 'nuevo'. Otras palabras, usar '|'.
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('ciudad');

Route::get('/ciudades/{ciudad}', 'CiudadController@show')
    ->where('ciudad', '[0-9]+')
    ->name('ciudad.show');

Route::post('/ciudades', 'CiudadController@store');

Route::get('/ciudades/{ciudad}/editar', 'CiudadController@edit')
    ->where('ciudad', '[0-9]+')
    ->name('ciudad.edit');

Route::put('/ciudades/{ciudad}', 'CiudadController@update');

Route::delete('/ciudades/{ciudad}', 'CiudadController@destroy')
    ->name('ciudad.destroy')
    ->middleware('admin');

Route::get('/estados/nuevo', 'EstadoController@create')
    ->name('estado.crear')
    ->middleware('admin');

Route::get('/estados/{orden?}/{accion?}', 'EstadoController@index')
// Cualquier nombre que comience con letra, luego letra, numero o '_'; excepto 'nuevo'. Otras palabras, usar '|'.
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('estado');

Route::get('/estados/{estado}', 'EstadoController@show')
    ->where('estado', '[0-9]+')
    ->name('estado.show');

Route::post('/estados', 'EstadoController@store');

Route::get('/estados/{estado}/editar', 'EstadoController@edit')
    ->where('estado', '[0-9]+')
    ->name('estado.edit');

Route::put('/estados/{estado}', 'EstadoController@update');

Route::delete('/estados/{estado}', 'EstadoController@destroy')
    ->name('estado.destroy')
    ->middleware('admin');

Route::get('/municipios/nuevo', 'MunicipioController@create')
    ->name('municipio.crear')
    ->middleware('admin');

Route::get('/municipios/{orden?}/{accion?}', 'MunicipioController@index')
// Cualquier nombre que comience con letra, luego letra, numero o '_'; excepto 'nuevo'. Otras palabras, usar '|'.
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('municipio');

Route::get('/municipios/{municipio}', 'MunicipioController@show')
    ->where('municipio', '[0-9]+')
    ->name('municipio.show');

Route::post('/municipios', 'MunicipioController@store');

Route::get('/municipios/{municipio}/editar', 'MunicipioController@edit')
    ->where('municipio', '[0-9]+')
    ->name('municipio.edit');

Route::put('/municipios/{municipio}', 'MunicipioController@update');

Route::delete('/municipios/{municipio}', 'MunicipioController@destroy')
    ->name('municipio.destroy')
    ->middleware('admin');

Route::get('/tipos/nuevo', 'TipoController@create')
    ->name('tipo.crear')
    ->middleware('admin');

Route::get('/tipos/{orden?}/{accion?}', 'TipoController@index')
// Cualquier nombre que comience con letra, luego letra, numero o '_'; excepto 'nuevo'. Otras palabras, usar '|'.
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('tipo');

Route::get('/tipos/{tipo}', 'TipoController@show')
    ->where('tipo', '[0-9]+')
    ->name('tipo.show');

Route::post('/tipos', 'TipoController@store');

Route::get('/tipos/{tipo}/editar', 'TipoController@edit')
    ->where('tipo', '[0-9]+')
    ->name('tipo.edit');

Route::put('/tipos/{tipo}', 'TipoController@update');

Route::delete('/tipos/{tipo}', 'TipoController@destroy')
    ->name('tipo.destroy')
    ->middleware('admin');

Route::get('/zonas/nuevo', 'ZonaController@create')
    ->name('zona.crear')
    ->middleware('admin');

Route::get('/zonas/{orden?}/{accion?}', 'ZonaController@index')
// Cualquier nombre que comience con letra, luego letra, numero o '_'; excepto 'nuevo'. Otras palabras, usar '|'.
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('zona');

Route::get('/zonas/{zona}', 'ZonaController@show')
    ->where('zona', '[0-9]+')
    ->name('zona.show');

Route::post('/zonas', 'ZonaController@store');

Route::get('/zonas/{zona}/editar', 'ZonaController@edit')
    ->where('zona', '[0-9]+')
    ->name('zona.edit');

Route::put('/zonas/{zona}', 'ZonaController@update');

Route::delete('/zonas/{zona}', 'ZonaController@destroy')
    ->name('zona.destroy')
    ->middleware('admin');

Route::get('/textos/nuevo', 'TextoController@create')
    ->name('texto.crear')
    ->middleware('admin');

Route::get('/textos/{orden?}/{accion?}', 'TextoController@index')
// Cualquier nombre que comience con letra, luego letra, numero o '_'; excepto 'nuevo'. Otras palabras, usar '|'.
    ->where('orden', '[a-zA-Z]+[a-zA-Z0-9_]+(?<!nuevo|crear)')
    ->where('accion', 'ver|descargar')
    ->name('texto');

Route::get('/textos/{texto}', 'TextoController@show')
    ->where('texto', '[0-9]+')
    ->name('texto.show');

Route::post('/textos', 'TextoController@store');

Route::get('/textos/{texto}/editar', 'TextoController@edit')
    ->where('texto', '[0-9]+')
    ->name('texto.edit');

Route::put('/textos/{texto}', 'TextoController@update');

Route::delete('/textos/{texto}', 'TextoController@destroy')
    ->name('texto.destroy')
    ->middleware('admin');

Route::get('/correoCita/{contacto}/{ruta?}', 'AgendaController@correoCita')
    ->where('ruta', '[12]')
    ->name('agenda.correoCita');

Route::get('/correoCitaPersonal/{cita}/{ruta?}', 'AgendaPersonalController@correoCita')
    ->where('ruta', '[12]')
    ->name('agendaPersonal.correoCita');

Route::get('/correoCitas/{user}', 'AgendaController@correoCitas')
    ->name('agenda.correoCitas');

Route::get('/correoTodasCitas/{desde?}/{hasta?}', 'AgendaController@correoTodasCitas')
    ->name('agenda.correoTodasCitas');

Route::get('/cumpleano/{user}', 'AgendaController@cumpleano')
    ->name('agenda.cumpleano');

Route::get('/avisos/asesor/{user}', 'UserController@avisos')
    ->where('user', '[0-9]+')
    ->name('users.avisos');

Route::get('pdf','PdfController@getIndex');
Route::get('pdf/generar','PdfController@getGenerar');

Route::get('/correo', function() {
//    $turnos = \App\Turno::whereBetween('turno', ['2019-12-02 00:00:00', '2019-12-08 23:59:59'])
//    $user   = \App\User::find(18);
//    $turnos = \App\Turno::where('user_id', $user->id)
//                        ->where('turno', '>', now('America/Caracas'))
//                        ->orderBy('turno')
//                        ->get();
//    $contacto   = \App\Contacto::find(21);
    $propiedad   = \App\Propiedad::find(64);
//    return new \App\Mail\TurnosErradosSemanaPasada($turnos);
//    return new \App\Mail\TurnosAsesor($user, $turnos);
    return new \App\Mail\ReporteCierre($propiedad);
});

//Route::get('/clientes/filtrar/{filtro}', 'ClienteController@filtro')
//    ->name('clientes.filtro');

//Route::get('/home', 'ClienteController@create')
//    ->name('clientes.create');
