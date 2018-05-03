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

Route::pattern('contactos', '[0-9]+');               // Para no crear conflictos con el resource contacto
Route::resource('contactos', 'ContactoController');

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

Route::get('/clientes/orden/{orden}', 'ClienteController@index')
    ->name('clientes.orden');

Route::pattern('clientes', '[0-9]+');               // Para no crear conflictos con el resource cliente
Route::resource('clientes', 'ClienteController');

Route::get('/agenda', 'AgendaController@index')
    ->name('agenda');

Route::get('/agenda/orden/{orden}', 'AgendaController@index')
    ->name('agenda.orden');

Route::post('/agenda', 'AgendaController@index')
    ->name('agenda.post');

Route::get('/agenda/{contacto}', 'AgendaController@show')
    ->where('contacto', '[0-9]+')
    ->name('agenda.show');

Route::get('/usuarios/{contacto}/editar', 'AgendaController@edit')
    ->name('agenda.edit');

Route::get('/reportes/tipo/{tipo}', 'ReporteController@index')
    ->name('reportes');

Route::post('/reportes', 'ReporteController@index')
    ->name('reportes.post');

Route::get('/reportes/chart/{chart}', 'ReporteController@chart')
    ->name('reportes.chart');

Route::post('/reportes/chart/{chart}', 'ReporteController@chart')
    ->name('reportes.chart.post');

//Route::get('/clientes/filtrar/{filtro}', 'ClienteController@filtro')
//    ->name('clientes.filtro');

//Route::get('/home', 'ClienteController@create')
//    ->name('clientes.create');
