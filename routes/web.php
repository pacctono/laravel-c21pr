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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/usuarios', 'UserController@index')
    ->name('users');

Route::get('/usuarios/orden/{orden}', 'UserController@index')
    ->name('users.orden');

Route::get('/usuarios/{user}', 'UserController@show')
    ->where('user', '[0-9]+')
    ->name('users.show');

Route::get('/usuarios/nuevo', 'UserController@create')
    ->name('users.create')
    ->middleware('admin');

Route::post('/usuarios', 'UserController@store');

Route::get('/usuarios/{user}/editar', 'UserController@edit')
    ->name('users.edit');

Route::put('/usuarios/{user}', 'UserController@update');

Route::delete('/usuarios/{user}', 'UserController@destroy')
    ->name('users.destroy')
    ->middleware('admin');

Route::get('/contactos/orden/{orden}', 'ContactoController@index')
    ->name('contactos.orden');

Route::pattern('contactos', '[0-9]+');  // Para no crear conflictos con el resource contacto
/* Las rutas de resource estan traducidos (crear y editar) en el metodo 'boot'
 * de "app/Providers/AppServiceProvider.php".*/
Route::resource('contactos', 'ContactoController');

Route::get('/contactos/{contacto}/{rutRetorno}', 'ContactoController@show')
    ->where('contacto', '[0-9]+')
    ->name('contactos.muestra');

Route::get('/turnos', 'TurnoController@index')
    ->name('turnos');

Route::get('/turnos/crear/{semana}', 'TurnoController@crear')
    ->name('turnos.crear');

Route::post('/turnos', 'TurnoController@store');

Route::get('/turnos/{turno}/editar', 'TurnoController@editar')
    ->name('turnos.editar');

Route::put('/turnos/{turno}', 'TurnoController@update');

Route::delete('/turnos/{turno}', 'TurnoController@destroy')
    ->name('turnos.destroy');

Route::get('/turnos/orden/{orden}', 'TurnoController@index')
    ->name('turnos.orden');

Route::get('/turnos/filtro', 'TurnoController@index');     // Para paginación con filtro.
Route::post('/turnos/filtro', 'TurnoController@index')
    ->name('turnos.post');

Route::get('/clientes/orden/{orden}', 'ClienteController@index')
    ->name('clientes.orden');

Route::pattern('clientes', '[0-9]+');               // Para no crear conflictos con el resource cliente
/* Las rutas de resource estan traducidos (crear y editar) en el metodo 'boot'
 * de "app/Providers/AppServiceProvider.php".*/
Route::resource('clientes', 'ClienteController');

Route::get('/agenda', 'AgendaController@index')
    ->name('agenda');

Route::get('/agenda/orden/{orden}', 'AgendaController@index')
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
    ->name('agenda.edit');

Route::put('/agenda/{cita}', 'AgendaController@update')
    ->name('agenda.update');

Route::get('/agendaPersonal/{agenda}', 'AgendaPersonalController@show')
    ->where('agenda', '[0-9]+')
    ->name('agendaPersonal.show');

Route::get('/agendaPersonal/crear', 'AgendaPersonalController@create')
    ->name('agendaPersonal.crear');

Route::post('/agendaPersonal', 'AgendaPersonalController@store')
    ->name('agendaPersonal.store');

Route::get('/agendaPersonal/{agenda}/editar', 'AgendaPersonalController@edit')
    ->name('agendaPersonal.edit');

Route::put('/agendaPersonal/{agenda}', 'AgendaPersonalController@update')
    ->where('agenda', '[0-9]+')
    ->name('agendaPersonal.update');

Route::get('/propiedades/orden/{orden}', 'PropiedadController@index')
    ->name('propiedades.orden');

Route::get('/propiedades/filtro', 'PropiedadController@index');     // Para paginación con filtro.
Route::post('/propiedades/filtro', 'PropiedadController@index')
    ->name('propiedades.post');

Route::get('/propiedades/grabar', 'PropiedadController@grabarArchivo')
    ->name('propiedades.grabar');

Route::pattern('propiedades', '[0-9]+');               // Para no crear conflictos con el resource propiedad
Route::resource('propiedades', 'PropiedadController')
    ->parameters(['propiedades' => 'propiedad']);

Route::get('/propiedades/{propiedad}/{rutRetorno}', 'PropiedadController@show')
    ->where('propiedad', '[0-9]+')
    ->name('propiedades.muestra');

Route::get('/reportes/tipo/{tipo}', 'ReporteController@index')
    ->name('reportes');

Route::post('/reportes', 'ReporteController@index')
    ->name('reportes.post');

Route::get('/reportes/chart/{chart}', 'ReporteController@chart')
    ->name('reportes.chart');

Route::post('/reportes/chart/{chart}', 'ReporteController@chart')
    ->name('reportes.chart.post');

Route::get('/reportes/contactosUser/{id}/{orden}', 'ReporteController@contactosXUser')
    ->name('reporte.contactosUser');

Route::get('/reportes/contactosDeseo/{id}/{orden}', 'ReporteController@contactosX')
    ->name('reporte.contactosDeseo');

Route::get('/reportes/contactosTipo/{id}/{orden}', 'ReporteController@contactosX')
    ->name('reporte.contactosTipo');

Route::get('/reportes/contactosOrigen/{id}/{orden}', 'ReporteController@contactosX')
    ->name('reporte.contactosOrigen');

Route::get('/reportes/contactosPrecio/{id}/{orden}', 'ReporteController@contactosX')
    ->name('reporte.contactosPrecio');

Route::get('/reportes/contactosZona/{id}/{orden}', 'ReporteController@contactosX')
    ->name('reporte.contactosZona');

Route::get('/reportes/contactosResultado/{id}/{orden}', 'ReporteController@contactosX')
    ->name('reporte.contactosResultado');

Route::get('/reportes/propiedadesCaracteristica/{id}/{orden}', 'ReporteController@propiedadesX')
    ->name('reporte.propiedadesCaracteristica');

Route::get('/reportes/propiedadesCiudad/{id}/{orden}', 'ReporteController@propiedadesX')
    ->name('reporte.propiedadesCiudad');

Route::get('/reportes/propiedadesMunicipio/{id}/{orden}', 'ReporteController@propiedadesX')
    ->name('reporte.propiedadesMunicipio');

Route::get('/reportes/propiedadesEstado/{id}/{orden}', 'ReporteController@propiedadesX')
    ->name('reporte.propiedadesEstado');

Route::get('/origenes', 'OrigenController@index')
    ->name('origen');

Route::get('/origenes/{origen}', 'OrigenController@show')
    ->where('origen', '[0-9]+')
    ->name('origen.show');

Route::get('/origenes/nuevo', 'OrigenController@create')
    ->name('origen.crear')
    ->middleware('admin');

Route::post('/origenes', 'OrigenController@store');

Route::get('/origenes/{origen}/editar', 'OrigenController@edit')
    ->name('origen.edit');

Route::put('/origenes/{origen}', 'OrigenController@update');

Route::delete('/origenes/{origen}', 'OrigenController@destroy')
    ->name('origen.destroy')
    ->middleware('admin');

Route::get('/deseos', 'DeseoController@index')
    ->name('deseo');

Route::get('/deseos/{deseo}', 'DeseoController@show')
    ->where('deseo', '[0-9]+')
    ->name('deseo.show');

Route::get('/deseos/nuevo', 'DeseoController@create')
    ->name('deseo.crear')
    ->middleware('admin');

Route::post('/deseos', 'DeseoController@store');

Route::get('/deseos/{deseo}/editar', 'DeseoController@edit')
    ->name('deseo.edit');

Route::put('/deseos/{deseo}', 'DeseoController@update');

Route::delete('/deseos/{deseo}', 'DeseoController@destroy')
    ->name('deseo.destroy')
    ->middleware('admin');

Route::get('/tipos', 'TipoController@index')
    ->name('tipo');

Route::get('/tipos/{tipo}', 'TipoController@show')
    ->where('tipo', '[0-9]+')
    ->name('tipo.show');

Route::get('/tipos/nuevo', 'TipoController@create')
    ->name('tipo.crear')
    ->middleware('admin');

Route::post('/tipos', 'TipoController@store');

Route::get('/tipos/{tipo}/editar', 'TipoController@edit')
    ->name('tipo.edit');

Route::put('/tipos/{tipo}', 'TipoController@update');

Route::delete('/tipos/{tipo}', 'TipoController@destroy')
    ->name('tipo.destroy')
    ->middleware('admin');

Route::get('/zonas', 'ZonaController@index')
    ->name('zona');

Route::get('/zonas/{zona}', 'ZonaController@show')
    ->where('zona', '[0-9]+')
    ->name('zona.show');

Route::get('/zonas/nuevo', 'ZonaController@create')
    ->name('zona.crear')
    ->middleware('admin');

Route::post('/zonas', 'ZonaController@store');

Route::get('/zonas/{zona}/editar', 'ZonaController@edit')
    ->name('zona.edit');

Route::put('/zonas/{zona}', 'ZonaController@update');

Route::delete('/zonas/{zona}', 'ZonaController@destroy')
    ->name('zona.destroy')
    ->middleware('admin');

Route::get('/textos', 'TextoController@index')
    ->name('texto');

Route::get('/textos/{texto}', 'TextoController@show')
    ->where('texto', '[0-9]+')
    ->name('texto.show');

Route::get('/textos/nuevo', 'TextoController@create')
    ->name('texto.crear')
    ->middleware('admin');

Route::post('/textos', 'TextoController@store');

Route::get('/textos/{texto}/editar', 'TextoController@edit')
    ->name('texto.edit');

Route::put('/textos/{texto}', 'TextoController@update');

Route::delete('/textos/{texto}', 'TextoController@destroy')
    ->name('texto.destroy')
    ->middleware('admin');

Route::get('/precios', 'PrecioController@index')
    ->name('precio');

Route::get('/precios/{precio}', 'PrecioController@show')
    ->where('precio', '[0-9]+')
    ->name('precio.show');

Route::get('/precios/nuevo', 'PrecioController@create')
    ->name('precio.crear')
    ->middleware('admin');

Route::post('/precios', 'PrecioController@store');

Route::get('/precios/{precio}/editar', 'PrecioController@edit')
    ->name('precio.edit');

Route::put('/precios/{precio}', 'PrecioController@update');

Route::delete('/precios/{precio}', 'PrecioController@destroy')
    ->name('precio.destroy')
    ->middleware('admin');

Route::get('/resultados', 'ResultadoController@index')
    ->name('resultado');

Route::get('/resultados/{resultado}', 'ResultadoController@show')
    ->where('resultado', '[0-9]+')
    ->name('resultado.show');

Route::get('/resultados/nuevo', 'ResultadoController@create')
    ->name('resultado.crear')
    ->middleware('admin');

Route::post('/resultados', 'ResultadoController@store');

Route::get('/resultados/{resultado}/editar', 'ResultadoController@edit')
    ->name('resultado.edit');

Route::put('/resultados/{resultado}', 'ResultadoController@update');

Route::delete('/resultados/{resultado}', 'ResultadoController@destroy')
    ->name('resultado.destroy')
    ->middleware('admin');

Route::get('/caracteristicas', 'CaracteristicaController@index')
    ->name('caracteristica');

Route::get('/caracteristicas/{caracteristica}', 'CaracteristicaController@show')
    ->where('caracteristica', '[0-9]+')
    ->name('caracteristica.show');

Route::get('/caracteristicas/nuevo', 'CaracteristicaController@create')
    ->name('caracteristica.crear')
    ->middleware('admin');

Route::post('/caracteristicas', 'CaracteristicaController@store');

Route::get('/caracteristicas/{caracteristica}/editar', 'CaracteristicaController@edit')
    ->name('caracteristica.edit');

Route::put('/caracteristicas/{caracteristica}', 'CaracteristicaController@update');

Route::delete('/caracteristicas/{caracteristica}', 'CaracteristicaController@destroy')
    ->name('caracteristica.destroy')
    ->middleware('admin');

Route::get('/ciudades', 'CiudadController@index')
    ->name('ciudad');

Route::get('/ciudades/{ciudad}', 'CiudadController@show')
    ->where('ciudad', '[0-9]+')
    ->name('ciudad.show');

Route::get('/ciudades/nuevo', 'CiudadController@create')
    ->name('ciudad.crear')
    ->middleware('admin');

Route::post('/ciudades', 'CiudadController@store');

Route::get('/ciudades/{ciudad}/editar', 'CiudadController@edit')
    ->name('ciudad.edit');

Route::put('/ciudades/{ciudad}', 'CiudadController@update');

Route::delete('/ciudades/{ciudad}', 'CiudadController@destroy')
    ->name('ciudad.destroy')
    ->middleware('admin');

Route::get('/estados', 'EstadoController@index')
    ->name('estado');

Route::get('/estados/{estado}', 'EstadoController@show')
    ->where('estado', '[0-9]+')
    ->name('estado.show');

Route::get('/estados/nuevo', 'EstadoController@create')
    ->name('estado.crear')
    ->middleware('admin');

Route::post('/estados', 'EstadoController@store');

Route::get('/estados/{estado}/editar', 'EstadoController@edit')
    ->name('estado.edit');

Route::put('/estados/{estado}', 'EstadoController@update');

Route::delete('/estados/{estado}', 'EstadoController@destroy')
    ->name('estado.destroy')
    ->middleware('admin');

Route::get('/municipios', 'MunicipioController@index')
    ->name('municipio');

Route::get('/municipios/{municipio}', 'MunicipioController@show')
    ->where('municipio', '[0-9]+')
    ->name('municipio.show');

Route::get('/municipios/nuevo', 'MunicipioController@create')
    ->name('municipio.crear')
    ->middleware('admin');

Route::post('/municipios', 'MunicipioController@store');

Route::get('/municipios/{municipio}/editar', 'MunicipioController@edit')
    ->name('municipio.edit');

Route::put('/municipios/{municipio}', 'MunicipioController@update');

Route::delete('/municipios/{municipio}', 'MunicipioController@destroy')
    ->name('municipio.destroy')
    ->middleware('admin');

Route::get('/emailcita/{contacto}', 'AgendaController@emailcita')
    ->name('agenda.emailcita');

Route::get('/emailcitas/{user}', 'AgendaController@emailcitas')
    ->name('agenda.emailcitas');

Route::get('/emailturnos/', 'AgendaController@emailturnos')
    ->name('agenda.emailturnos');

Route::get('/emailtodascitas/{tipo}', 'AgendaController@emailtodascitas')
    ->name('agenda.emailtodascitas');

Route::get('/cumpleano/{user}', 'AgendaController@cumpleano')
    ->name('agenda.cumpleano');

//Route::get('/clientes/filtrar/{filtro}', 'ClienteController@filtro')
//    ->name('clientes.filtro');

//Route::get('/home', 'ClienteController@create')
//    ->name('clientes.create');
