<?php

namespace App\Http\Controllers;

use App\Turno;
use App\User;
use App\Bitacora;
use App\Aviso;
use \App\Mail\TurnosAsesor;
use \App\Mail\TurnosErradosSemanaPasada;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;                 // PC
use Calendar;
use App\MisClases\Fecha;
use App\MisClases\General;               // PC

class TurnoController extends Controller
{
    protected $tipo = 'Turnos';
    protected $lineasXPagina = 20/*General::LINEASXPAGINA*/;

    protected function crearSemanas() {
        $ultSemanaCreada = True;
        $ultimaSemana    = null;
        for ($d = 0; $d < 11; $d++) {
            $lunes = Fecha::primerLunesDePrimeraSemana()->addWeeks($d);   // Proximos once lunes.
            $turno = Turno::whereDate('turno', $lunes->format('Y-m-d'))
                            ->whereTime('turno', '08:00:00')
                            ->get();
            $noExiste = $turno->isEmpty();    // False: Existe; True: No existe (isEmpty).
            $semanas[$d] = array($lunes, $noExiste);
            if ($ultSemanaCreada = ($ultSemanaCreada and !$noExiste))
                if ($lunes > now(Fecha::$ZONA)) $ultimaSemana = $turno;
        }
        return [$semanas, $ultimaSemana->first()];
    }
    protected static function turnoNuevo($semana, $index, $hora, $nroId) {
        return [
            'turno'  => Fecha::primerLunesDePrimeraSemana()
                            ->addWeeks($semana)
                            ->addDays($index)
                            ->format("Y-m-d $hora:00:00"),
            'user_id'   => $nroId,
        ];
    }
    public function index($orden=null, $accion='html')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        $title = 'Listado de ' . $this->tipo;
        $ruta = request()->path();
        $periodo = request()->all();
        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
//        dd($periodo);
// Todo se inicializa, cuando se selecciona 'turnos' desde el menú horizontal.
        if (('GET' == request()->method()) and ('' == $orden) and (0 == count($periodo))) {
            session(['rPeriodo' => '', 'fecha_desde' => '', 'fecha_hasta' => '',
                        'asesor' => '0']);
        }

        $diaSemana = Fecha::$diaSemana;
        list ($semanas, $ultimaSemanaCreada) = $this->crearSemanas();   // $semanas es arreglo de arreglos con [0] la fecha de los proximos once lunes y [1] bool: semana no creada.
/*        for ($d = 0; $d < 11; $d++) {
            $semanas[$d] = Fecha::primerLunesDePrimeraSemana()
                                    ->addWeeks($d);            // Proximos once lunes.
        }*/
//        dd($semanas);
/*
 * Manejo de las variables de la forma superior. $periodo (radios, fecha_desde, fecha_hasta y asesor).
 * Cuando el arreglo $periodo contiene un solo item, este es el número de página (page=n).
 * Si el arreglo $periodo está vacio (count($arreglo) == 0), es una ruta 'GET' con o sin $orden.
 * Si $periodo tiene más de 1 item. Fue seleccionado un radio y/o las fechas y el asesor.
 */
        if (1 >= count($periodo)) {
            $rPeriodo    = session('rPeriodo', '');
            $fecha_desde = session('fecha_desde', '');
            $fecha_hasta = session('fecha_hasta', '');
            $asesor      = session('asesor', '0');
        } else {    // Se ha solicitado una fecha o asesor especifico. Forma al inicio de la pagina.
            if ($movil) $periodo['periodo'] = 'intervalo';
            $rPeriodo = $periodo['periodo'];            // Radio periodo.
            $fecha_min = (new Carbon(Turno::min('turno')));
            $fecha_max = (new Carbon(Turno::max('turno')));
            if (isset($periodo['asesor'])) {
                $asesor = $periodo['asesor'];
                if ((!isset($periodo['fecha_desde'])) or ('' == $periodo['fecha_desde']))
                    $periodo['fecha_desde'] = $fecha_min;
                if ((!isset($periodo['fecha_hasta'])) or ('' == $periodo['fecha_hasta']))
                    $periodo['fecha_hasta'] = $fecha_max;
            }
            else $asesor = 0;
            list ($fecha_desde, $fecha_hasta) = Fecha::periodo($periodo, $fecha_min, $fecha_max);
        }
//        dd($periodo, $fecha_desde, $fecha_hasta);
// En caso de volver luego de haber enviado un correo, ver el metodo 'correocita', en AgendaController.
        $alertar = 0;
        if (isset($_GET['correo']) and ($correo = $_GET['correo'])) {
            if ('s' == $correo) {
                $alertar = 2;
            } elseif ('N' == $correo) {
                $alertar = -1;
            }
        }
        if ('' == $orden or is_null($orden)) {
            $orden = 'turno';
        }
        if (Auth::user()->is_admin) {
//            $users   = User::all();         // Todos los usuarios. Incluye '1' porque en turnos hay feriado.
            $users  = User::where('activo', True)->where('socio', False)->get();
            $turnos = Turno::where('id', '>', 0);   // condición dummy, solo para continuar armando la consulta.
        } else {
            $asesor = Auth::user()->id;
            $user   = User::findOrFail($asesor);
            $title .= ' de ' . $user->name;
            $turnos = $user->turnos();
        }
    
        if (0 < $asesor) {      // Se selecciono un asesor o el conectado no es administrador.
            $turnos = $turnos->where('user_id', $asesor);
        }
        //dd($fecha_desde, $fecha_hasta, $turnos->get());
        if ('' != $fecha_desde and '' != $fecha_hasta) {    // No se seleccionaron fechas.
            $fecha_desde = substr($fecha_desde, 0, 10);
            $fecha_hasta = substr($fecha_hasta, 0, 10);
            $turnos = $turnos->whereBetween('turno',
                                [$fecha_desde, $fecha_hasta.' 23:59:59']);  // Cualquier hora antes de medianoche.
        } else {
            $turnos = $turnos->whereDate('turno', '>=', now(Fecha::$ZONA)->format('Y-m-d'));
        }
//        dd($periodo, $fecha_desde, $fecha_hasta);
        $turnos = $turnos->orderBy($orden);   // Ordenar los items de los turnos.
        if ('user_id' == $orden) {              // Si se pidió ordenar por id de usuario,
            $turnos = $turnos->orderBy('turno');   // ordenar por turno en cada usuario.
        }
	//dd($periodo, $rPeriodo);
        if (isset($rPeriodo)) {
	    if (false === strpos($rPeriodo, 'mes')) $lineasXPagina = $this->lineasXPagina;
            else $lineasXPagina = Turno::whereBetween('turno', [$fecha_desde, $fecha_hasta.' 23:59:59'])->count();
        } else $lineasXPagina = $this->lineasXPagina;
        if (!isset($lineasXPagina) or (0 >= $lineasXPagina)) $lineasXPagina = $this->lineasXPagina;
        if ($movil or ('html' != $accion)) $turnos = $turnos->get();
        else $turnos = $turnos->paginate($lineasXPagina);               // Pagina la impresión de 10 en 10
// Devolver las fechas sin la hora. Los diez primeros caracteres son: yyyy-mm-dd.
        session(['rPeriodo' => $rPeriodo, 'fecha_desde' => $fecha_desde,    // Asignar valores en sesión.
                    'fecha_hasta' => $fecha_hasta, 'asesor' => $asesor]);
        if ('html' == $accion)
            return view('turnos.index', compact('title', 'users', 'turnos',
                    'ruta', 'diaSemana', 'semanas', 'ultimaSemanaCreada', 'rPeriodo',
                    'fecha_desde', 'fecha_hasta', 'asesor', 'alertar', 'orden',
                    'movil', 'accion'));
        $html = view('turnos.index', compact('title', 'users', 'turnos',
                    'ruta', 'diaSemana', 'semanas', 'ultimaSemanaCreada', 'rPeriodo',
                    'fecha_desde', 'fecha_hasta', 'asesor', 'alertar', 'orden',
                    'movil', 'accion'))
                ->render();
        General::generarPdf($html, 'turnos', $accion);
    }

    public function calendario($miCalendario=0)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        $variables = request()->all();
        //dd($variables);
        if ('GET' == request()->method()) session(['asesor' => '0']);
        else {
            session(['asesor' => $variables['asesor']]);
        }
        $asesor = session('asesor', '0');

        $turnos = Turno::where('id', '>', 0);   // condición dummy, solo para continuar armando la consulta.

        if (Auth::user()->is_admin) {
            $users  = User::where('activo', True)->where('socio', False)->get();
            $texto  = '';
            $miCal  = 0;
        } else {
            if ($miCalendario) {
                $asesor = Auth::user()->id;
                $texto  = 'Todos los Turnos';
                $miCal  = 0;
            }
            else {
                $asesor = 0;           // No pareciera necesario, pero, al diablo.
                $texto  = 'Mis Turnos';
                $miCal  = 1;
            }
            $users  = '';
        }
        if (0 < $asesor) $turnos = $turnos->where('user_id', $asesor);
    
        $data = $turnos->get();
        $eventos = [];
        if($data->count()) {
            foreach ($data as $key => $turno) {
                $start = new \DateTime($turno->turno . ' +30 minutes');     // '\' carpeta de la clase.
                $end   = clone $start;
                if ('M' == $turno->tipo_tur) $end->add(new \DateInterval('PT4H'));       // Periodo usando ('PT') hora, minuto o segundo. Solo 'P' si es dia, mes o año.
                else $end->add(new \DateInterval('PT5H'));       // Periodo usando ('PT') hora, minuto o segundo. Solo 'P' si es dia, mes o año.
                if ($start <= now()) {
                    if ('C' == $turno->tarde) $color = 'red';
                    elseif (('m' == $turno->tarde) or ('t' == $turno->tarde)) $color = 'orange';
                    else $color = 'blue';
                }
                else $color = 'yellow';
                if ('' == $turno->tarde) $descripcion = '';
                else $descripcion = $turno->descripcion;
                $eventos[] = Calendar::event(
                    $turno->user->name, // Titulo
                    false,              // Todo el dia
                    $start,             // Comienzo
                    $end,               // Final
                    null,               // id
                    [
                        'color' => $color,
                        'descripcion' => $descripcion
                    ]
                );
            }
        }
        if (Auth::user()->is_admin) {
            $header = [
                        'left' => 'prev,next today',
                         'center' => 'title',
                         'right' => 'month,agendaWeek,agendaDay,listMonth,proximos'
                    ];
            $lstTurnos = False;
            $proximos = [
                        'text' => "Proximos Turnos (PDF)",
                        "click" => "function() { location.href = '/turnos'; }",
                    ];
        } else {
            $header = [
                        'left' => 'prev,next today',
                         'center' => 'title',
                         'right' => 'month,listMonth,turnos,proximos'
                    ];
            $lstTurnos = [
                        'text' => $texto,
                        "click" => "function() { location.href = '/turnos/calendario/".
                                                                $miCal."'; }",
                    ];
            $proximos = [
                        'text' => "Mis Proximos Turnos (PDF)",
                        "click" => "function() { location.href = '/turnos'; }",
                    ];
        }
        $calendar = Calendar::addEvents($eventos)->setOptions([
                        'header' => $header,
                        'customButtons' => [
                            'turnos' => $lstTurnos,
                            'proximos' => $proximos,
                        ],
                        'firstDay' => 1,			// El primer dia de la semana es el lunes.
                        'hiddenDays' => [0],		// No se muestra el domingo.
                        'fixedWeekCount' => false,	// Numero de semana variable, dependiendo del mes.
                        'weekNumbers' => false,		// Muestra el numero de la semana, con respecto el año.
                        'aspectRatio' => 2.0,		// Mientras mas grande menos altura.
                        'businessHours' => [
                            [
                                'dow' => [ 1, 2, 3, 4, 5 ],
                                'start' => '07:00',
                                'end' => '20:00'
                            ],
                            [
                                'dow' => [ 6 ],
                                'start' => '07:00',
                                'end' => '14:00'
                            ]
                        ]
                   ]);
        if (Auth::user()->is_admin)
            $calendar = $calendar->setCallbacks([ //set fullcalendar callback options (will not be JSON encoded)
                        'eventClick' => 'function(ev, jq, vista) {
                                        const milisDia = 1000*60*60*24;
                                        const comienzo = ev.start;
                                        const ahora = new Date();
                                        const hoy   = new Date(ahora.getFullYear(),
                                                            ahora.getMonth(), ahora.getDate());
                                        //const fec   = comienzo.toDate();  // Convierte <moment> a Javascript.
                                        const fecha = new Date(comienzo.year(),
                                                            comienzo.month(), comienzo.date());
                                        if (fecha <= hoy) {
                                            alert("No puede modificar fechas antes o iguales a \'hoy\'.");
                                            return;
                                        }
                                        var sumarDias;
                                        if (0 == hoy.getDay()) sumarDias = 1;
                                        else sumarDias = 1 - hoy.getDay();
                                        const plunes = new Date(ahora.getFullYear(), ahora.getMonth(),
                                                            ahora.getDate() + sumarDias);
                                        /*const fecha = new Date(fec.getFullYear(), fec.getMonth(),
                                                            fec.getDate());*/
                                        const dtiempo = fecha.getTime() - plunes.getTime();
                                        const dias  = dtiempo/milisDia;
                                        const semana = parseInt(dias/7);
                                        //alert(`Diferencia entre primer lunes(${plunes}) y fecha evento(${fecha}) de evento:${dias}`);
                                        //alert(`Semana:${semana}`);
                                        location.href = `/turnos/crear/${semana}`;
                        }',
                        'eventMouseOver' => 'function(ev, jq, vista) {
                                        alert(`Raton arriba`);
                        }',
                   ]);

        $diaSemana = Fecha::$diaSemana;
        list ($semanas, $ultimaSemanaCreada) = $this->crearSemanas();   // $semanas es arreglo de arreglos con [0] la fecha de los proximos once lunes y [1] bool: semana no creada.
/*        for ($d = 0; $d < 11; $d++) {
            $semanas[$d] = Fecha::primerLunesDePrimeraSemana()
                                    ->addWeeks($d);            // Proximos once lunes.
        }*/

        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
        return view('turnos.calendario',
                compact('calendar', 'users', 'asesor', 'diaSemana', 'ultimaSemanaCreada',
                        'semanas', 'movil'));

    }

    public function crear($semana = null)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (1 != Auth::user()->is_admin) {
            return redirect('home');
        }

        if ('' == $semana or is_null($semana)) {
            $semana = 0;
        }

        $fecha = Fecha::primerLunesDePrimeraSemana()->addWeeks($semana);
        $turnoCrear = Turno::whereDate('turno', $fecha->format('Y-m-d'))
                            ->whereTime('turno', '08:00:00')->get();
// Revisa si el turno no existe y si existe, que tenga asignado un asesor (!= 0).
        if ($turnoCrear->isNotEmpty() and (0 < $turnoCrear->first()->user_id)) {
            return redirect()->route('turnos.editar', $semana);
        }

        $diaSemana = Fecha::$diaSemana;
        array_shift($diaSemana);                // Desaparece el domingo y comienza el lunes.
        $title = 'Crear ' . $this->tipo . ' para la semana que comienza el lunes, ' . 
                    $fecha->format('d/m/Y');

        $users = User::where('activo', True)->where('socio', False)->where('id', '>', 1)
                        ->get(['id', 'name']);     // Todos los usuarios (asesores) activos.

        $min = 0;
        $max = $users->count() - 1;
        $idAsesores = [];
        foreach ($users as $user) $idAsesores[] = $user->id;    // Solo los ids de cada asesor posible.

// Se prepara el arreglo '$ids' con los ids de los asesores que estuvieron el lunes y sabado de la semana pasada.
        $lunesAnt = Fecha::primerLunesDePrimeraSemana()->addWeeks($semana-1)->format("Y-m-d");
        $sabadoAnt = Fecha::primerLunesDePrimeraSemana()->addWeeks($semana)->subDays(2)
                                ->format("Y-m-d");
// Creamos los arreglos de asesores de los ultimos 24 lunes/sabados anteriores.                                
        $asesoresLunesAnt = [];
        $asesoresSabadosAnt = [];
        for ($i=1; $i<=24; $i++) {
            $arTmp = Turno::whereBetween('turno', [$lunesAnt.' 00:00', $lunesAnt.' 11:59']) // Solo turno de mañana.
                                ->get(['user_id'])->all();
            if (isset($arTmp) and (0 < count($arTmp))) {
                $idTmp = $arTmp[0]->user_id;
                if (!in_array($idTmp, $asesoresLunesAnt) and
                    in_array($idTmp, $idAsesores) and (1 < $idTmp))
                    $asesoresLunesAnt[] = $idTmp;
            }
            $arTmp = Turno::whereBetween('turno', [$sabadoAnt.' 00:00', $sabadoAnt.' 11:59'])
                                ->get(['user_id'])->all();
            if (isset($arTmp) and (0 < count($arTmp))) {
                $idTmp = $arTmp[0]->user_id;
                if (!in_array($idTmp, $asesoresSabadosAnt) and
                    in_array($idTmp, $idAsesores) and (1 < $idTmp))
                    $asesoresSabadosAnt[] = $idTmp;
            }
            $lunesAnt = (new Carbon($lunesAnt))->subWeek()->format("Y-m-d");
            $sabadoAnt = (new Carbon($sabadoAnt))->subWeek()->format("Y-m-d");
        }
        $asesoresLunesAnt = array_reverse(  // Al revertir el arreglo, quedan primero los id que tienen mas tiempo sin hacer turno.
                                array_merge($asesoresLunesAnt,      // Une los ids de los turnos anteriores, con los que no han hecho (nuevos).
                                    array_diff($idAsesores, $asesoresLunesAnt)));   // Devuelve los id de quienes no han hecho turno.
        $asesoresSabadosAnt = array_reverse(// Al revertir el arreglo, quedan primero los id que tienen mas tiempo sin hacer turno.
                                array_merge($asesoresSabadosAnt,    // Une los ids de los turnos anteriores, con los que no han hecho (nuevos).
                                    array_diff($idAsesores, $asesoresSabadosAnt))); // Devuelve los id de quienes no han hecho turno.
        //dd($idAsesores, $asesoresLunesAnt, $asesoresSabadosAnt);
        if ((isset($asesoresLunesAnt) and (0 < count($asesoresLunesAnt))) and // Tenemos los turnos de la semana pasada?
            (isset($asesoresSabadosAnt) and (0 < count($asesoresSabadosAnt)))) {
            $ids = [];
            $idLunes = array_shift($asesoresLunesAnt);  // Asesor del lunes en la mañana.
            $idSabado = $asesoresSabadosAnt[0];
            if ($idSabado == $idLunes) $idSabado = $asesoresSabadosAnt[1];
/*
// user_id = 1 (administrador/feriado) no debo incluirlo en el 'WHERE' de '$users... = Turno...', porque
// si el lunes/sabado anterior es feriado, no queremos que me arroje arreglos vacios.
            foreach ($asesoresLunesAnt as $id) {
                if (!in_array($id->user_id, $ids) and (1 < $id->user_id)) $ids[] = $id->user_id;
            }
            if (isset($asesoresSabadosAnt) and (!in_array($asesoresSabadosAnt[0]->user_id, $ids))) {
                if (1 < $asesoresSabadosAnt[0]->user_id) {
                    $idSabado = $asesoresSabadosAnt[0]->user_id;
                    $ids[] = $idSabado;
                }
            }
            $idsInicial = $ids;
 */
// Ya tenemos el arreglo '$ids'.
            $j = -1;
            $nuevosTurnos = [];
            $nuevosTurnos[] = $this::turnoNuevo($semana, 0, '08', $idLunes);    // Lunes en la ma#ana.
            $ids[] = $idLunes;  // Asesor del lunes en la mañana.
            for ($i = 1; $i < 3; $i++) {
                if ($max >= count($ids)) {
                    list($nroId, $j) = General::idAlAzar($j, $ids, $min, $max, $idAsesores);
                } else {	// Se acabaron los id de asesores, ubicaremos el resto de turnos de acuerdo al arreglo $ids.
                    list($nroId, $j) = General::idDelArreglo($j, $ids, $i);
                }
                $nuevosTurnos[] = $this::turnoNuevo($semana, $i, '08', $nroId);// Martes y miercoles en la ma#ana.
            }
            for ($i = 0; $i < 3; $i++) {
                if ($max >= count($ids)) {
                    list($nroId, $j) = General::idAlAzar($j, $ids, $min, $max, $idAsesores);
                } else {	// Se acabaron los id de asesores, ubicaremos el resto de turnos de acuerdo al arreglo $ids.
                    list($nroId, $j) = General::idDelArreglo($j, $ids, $i);
                }
                $nuevosTurnos[] = $this::turnoNuevo($semana, $i, '12', $nroId);// Lunes, martes y miercoles en la tarde.
            }
            for ($i = 3; $i < 5; $i++) {
                if ($max >= count($ids)) {
                    list($nroId, $j) = General::idAlAzar($j, $ids, $min, $max, $idAsesores, $idSabado);
                } else {	// Se acabaron los id de asesores, ubicaremos el resto de turnos de acuerdo al arreglo $ids.
                    list($nroId, $j) = General::idDelArreglo($j, $ids, $i, $idSabado);
                }
                $nuevosTurnos[] = $this::turnoNuevo($semana, $i, '08', $nroId);// Jueves y viernes en la ma#ana.
            }
            $nuevosTurnos[] = $this::turnoNuevo($semana, 5, '08', $idSabado);   // Sabado en la ma#ana.
            for ($i = 3; $i < 5; $i++) {
                if ($max >= count($ids)) {
                    list($nroId, $j) = General::idAlAzar($j, $ids, $min, $max, $idAsesores, $idSabado);
                } else {	// Se acabaron los id de asesores, ubicaremos el resto de turnos de acuerdo al arreglo $ids.
                    list($nroId, $j) = General::idDelArreglo($j, $ids, $i, $idSabado);
                }
                $nuevosTurnos[] = $this::turnoNuevo($semana, $i, '12', $nroId);// Jueves y viernes en la tarde.
            }
            //dd("min: $min", "max: $max", 'ids:', $ids, 'idSabado:', $idSabado, 'nuevosTurnos:', $nuevosTurnos, 'idAsesores: ', $idAsesores);
            foreach ($nuevosTurnos as $turno) {
                $turnoEditar = Turno::where('turno', $turno['turno'])->get();
                if ($turnoEditar->isEmpty()) {
                    Turno::create([
                        'turno'     => $turno['turno'],
                        'user_id'   => $turno['user_id'],
                        'user_creo' => Auth::user()->id,
                    ]);
                } else {
                    $turnoEditar->update($turno);
                }
            }
            return redirect()->route('turnos.editar', $semana);
        }   // if ((isset($asesoresLunesAnt) and (0 < count($asesoresLunesAnt))) and (isset($asesoresSabadosAnt) and (0 < count($asesoresSabadosAnt))))
// Crea el arreglo '$dia' con la fecha de cada dia de la semana; para el cual, se va a crear el turno.
        for ($d = 0; $d < 6; $d++) {
// Si '$fecha' no la inicia; nuevamente, es modificada cada vez que se ejecuta este comando.
            $fecha = Fecha::primerLunesDePrimeraSemana()->addWeeks($semana);
            $dia[$d] = $fecha->addDays($d)->format('Y-m-d');    // Fecha de cada dia.
        }
// Crea el arreglo '$semanas' con la fecha del lunes de cada una de las proximas once semanas.
        list ($semanas, $ultimaSemanaCreada) = $this->crearSemanas();   // $semanas es arreglo de arreglos con [0] la fecha de los proximos once lunes y [1] bool: semana no creada.
/*        for ($d = 0; $d < 11; $d++) {
            $semanaInicial = Fecha::primerLunesDePrimeraSemana();
            $semanas[$d] = $semanaInicial->addWeeks($d);            // Proximos once lunes.
        }*/

        return view('turnos.crear', compact('title', 'diaSemana', 'dia', 'users',
                                            'ultimaSemanaCreada', 'semanas', 'semana'));
    }

    public function store(Request $request)
    {
        $fechas = $request->validate([             // $fechas = request()->all();
            'u0' => ['required'],
            'f0' => '',
            'u1' => ['required'],
            'f1' => '',
            'u2' => ['required'],
            'f2' => '',
            'u3' => ['required'],
            'f3' => '',
            'u4' => ['required'],
            'f4' => '',
            'u5' => ['required'],
            'f5' => '',
            'u6' => ['required'],
            'f6' => '',
            'u7' => ['required'],
            'f7' => '',
            'u8' => ['required'],
            'f8' => '',
            'u9' => ['required'],
            'f9' => '',
            'u10' => ['required'],
            'f10' => '',
            'semana' => ''
        ], [
            'u0.required' => 'No seleccionó el asesor para el turno del lunes en la mañana',
            'u1.required' => 'No seleccionó el asesor para el turno del martes en la mañana',
            'u2.required' => 'No seleccionó el asesor para el turno del miercoles en la mañana',
            'u3.required' => 'No seleccionó el asesor para el turno del lunes en la tarde',
            'u4.required' => 'No seleccionó el asesor para el turno del martes en la tarde',
            'u5.required' => 'No seleccionó el asesor para el turno del miercoles en la tarde',
            'u6.required' => 'No seleccionó el asesor para el turno del jueves en la mañana',
            'u7.required' => 'No seleccionó el asesor para ipaspudo_2018-04-04-17.tgzel turno del viernes en la mañana',
            'u8.required' => 'No seleccionó el asesor para el turno del sábado en la mañana',
            'u9.required' => 'No seleccionó el asesor para el turno del viernes en la tarde',
            'u10.required' => 'No seleccionó el asesor para el turno del sábado en la tarde',
        ]);
        //dd($fechas);

        for ($i = 0; $i < 11; $i++) {
            $data['turno']  = new Carbon($fechas['f'.$i] . ':00:00');
            $data['user_id']   = $fechas['u'.$i];
            $data['user_creo'] = Auth::user()->id;            

            Turno::create([
                'turno'  => $data['turno'],
                'user_id'   => $data['user_id'],
                'user_creo' => $data['user_creo'],
            ]);
        }
        $semana = $fechas['semana'];

        if ('' == $semana or is_null($semana)) {
            return redirect()->route('turnos');
        } else {
            return redirect()->route('turnos.crear', $semana);
        }
    }

    public function editar($semana = null)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!Auth::user()->is_admin) {
            return redirect('home');
        }

        if ('' == $semana or is_null($semana)) {
            $semana = 0;
        }

        $fecha = Fecha::primerLunesDePrimeraSemana()->addWeeks($semana);
        $turnoEditar = Turno::whereDate('turno', $fecha->format('Y-m-d'))
                            ->whereTime('turno', '08:00:00')
                            ->get();
        if (($turnoEditar->isEmpty()) or
            ($turnoEditar->isNotEmpty() and (0 == $turnoEditar->first()->user_id))) {
            return redirect()->route('turnos.crear', $semana);
        }

        $diaSemana = Fecha::$diaSemana;         // Domingo, Lunes, Martes, ...
        array_shift($diaSemana);                // Desaparece el domingo y comienza el lunes.
        $title = 'Editar ' . $this->tipo . ' para la semana que comienza el lunes, ' . 
                    $fecha->format('d/m/Y');

        for ($d = 0; $d < 6; $d++) {
            $fecha = Fecha::primerLunesDePrimeraSemana()->addWeeks($semana);
            $dia[$d] = $fecha->addDays($d)->format('Y-m-d');    // Fecha de cada dia de la semana a editar.
        }

        $users = User::where('activo', True)->where('socio', False)
                        ->get(['id', 'name']);     // Todos los usuarios (asesores) activos.

        list ($semanas, $ultimaSemanaCreada) = $this->crearSemanas();   // $semanas es arreglo de arreglos con [0] la fecha de los proximos once lunes y [1] bool: semana no creada.

        $fecha1 = Fecha::primerLunesDePrimeraSemana()->addWeeks($semana);   // Lunes
        $fecha2 = Fecha::primerLunesDePrimeraSemana()->addWeeks($semana)->addDays(6);                              // Domingo
        $turnos = Turno::whereBetween('turno', [$fecha1->format('Y-m-d'), $fecha2->format('Y-m-d')])
                    ->orderBy('id')         // Puede estar demas, pero, me aseguro el orden correcto.
                    ->get()->all();         // Turnos a editar.
//        dd($turnos);
        $lunes = $turnos[0];                // Datos del turno del primer dia de la semana a editar.

        $eventos = [];
        $primerDia = (new Carbon($lunes->turno->format('Y-m-01')))->startOfDay();
        $dataMes = Turno::where('turno', '>=', $primerDia)->get();
        if($dataMes->count()) {
            foreach ($dataMes as $key => $turno) {
                $start = new \DateTime($turno->turno . ' +30 minutes');     // '\' carpeta de la clase.
                $end   = clone $start;
                if ('M' == $turno->tipo_tur) $end->add(new \DateInterval('PT4H'));       // Agrega 4 horas. Periodo usando ('PT') hora, minuto o segundo. Solo 'P' si es dia, mes o año.
                else $end->add(new \DateInterval('PT5H'));       // Agrega 5 horas. Periodo usando ('PT') hora, minuto o segundo. Solo 'P' si es dia, mes o año.
                if ($start <= now()) {
                    if ('C' == $turno->tarde) $color = 'red';
                    elseif (('m' == $turno->tarde) or ('t' == $turno->tarde)) $color = 'orange';
                    else $color = 'blue';
                }
                else $color = 'yellow';
                $eventos[] = Calendar::event(
                    $turno->user->name,
                    false,
                    $start,
                    $end,
                    null,               // id
                    [
                        'color' => $color,
                    ]
                );
            }
        }
        $calendar = Calendar::addEvents($eventos)->setOptions([
//                        'header' => false,
                        'header' => [
                            'left' => false,
                            'center' => 'title',
                            'right' => false,
                        ],
                        'editable' => true,        // El evento no puede ser modificado.
                        'eventDurationEditable' => false,   // La duracion del evento puede ser modificado.
                        //'eventStartEditable' => false,  // El comienzo del evento puede ser modificado.
                        'droppable' => true,    // Determines if external jQuery UI draggables can be dropped onto the calendar.
                        //'eventResourceEditable' => true,    // El evento puede ser arrastrado entre recursos.
                        'firstDay' => 1,			// El primer dia de la semana es el lunes.
                        'hiddenDays' => [0],		// No se muestra el domingo.
                        'fixedWeekCount' => false,	// Numero de semana variable, dependiendo del mes.
                        'weekNumbers' => true,		// Muestra el numero de la semana, con respecto el año.
                        'defaultDate' => $lunes->turno->format('Y-m-d'),	// Fecha del mes a mostrar.
                        'columnHeader' => false,	// esconde los titulos de los dias de la semana.
                        'aspectRatio' => 3,		    // Mientras mas grande menos altura.
                   ]);
        if (Auth::user()->is_admin)
            $calendar = $calendar->setCallbacks([ //set fullcalendar callback options (will not be JSON encoded)
                        'eventClick' => 'function(ev, jq, vista) {
                                        const milisDia = 1000*60*60*24;
                                        const comienzo = ev.start;
                                        const ahora = new Date();
                                        const hoy   = new Date(ahora.getFullYear(),
                                                            ahora.getMonth(), ahora.getDate());
                                        //const fec   = comienzo.toDate();  // Convierte <moment> a Javascript.
                                        const fecha = new Date(comienzo.year(),
                                                            comienzo.month(), comienzo.date());
                                        if (fecha <= hoy) {
                                            alert("No puede modificar fechas antes o iguales a \'hoy\'.");
                                            return;
                                        }
                                        var sumarDias;
                                        if (0 == hoy.getDay()) sumarDias = 1;
                                        else sumarDias = 1 - hoy.getDay();
                                        const plunes = new Date(ahora.getFullYear(), ahora.getMonth(),
                                                            ahora.getDate() + sumarDias);
                                        /*const fecha = new Date(fec.getFullYear(), fec.getMonth(),
                                                            fec.getDate());*/
                                        const dtiempo = fecha.getTime() - plunes.getTime();
                                        const dias  = dtiempo/milisDia;
                                        const semana = parseInt(dias/7);
                                        location.href = `/turnos/crear/${semana}`;
                        }',
                   ]);

        $turno = $lunes;    // Fecha del lunes de la semana a editar.
        //dd($turnos, $lunes, $turno);
        return view('turnos.editar',
                    compact('title', 'diaSemana', 'dia', 'users', 'semanas', 'semana',
                            'turnos', 'turno', 'ultimaSemanaCreada', 'calendar'));
    }

    public function update(Request $request, Turno $turno)
    {
        $fechas = $request->validate([             // $fechas = request()->all();
            'u0' => ['required'],
            'f0' => '',
            'u1' => ['required'],
            'f1' => '',
            'u2' => ['required'],
            'f2' => '',
            'u3' => ['required'],
            'f3' => '',
            'u4' => ['required'],
            'f4' => '',
            'u5' => ['required'],
            'f5' => '',
            'u6' => ['required'],
            'f6' => '',
            'u7' => ['required'],
            'f7' => '',
            'u8' => ['required'],
            'f8' => '',
            'u9' => ['required'],
            'f9' => '',
            'u10' => ['required'],
            'f10' => '',
            'semana' => ''
        ], [
            'u0.required' => 'No seleccionó el asesor para el turno del lunes en la mañana',
            'u1.required' => 'No seleccionó el asesor para el turno del martes en la mañana',
            'u2.required' => 'No seleccionó el asesor para el turno del miercoles en la mañana',
            'u3.required' => 'No seleccionó el asesor para el turno del lunes en la tarde',
            'u4.required' => 'No seleccionó el asesor para el turno del martes en la tarde',
            'u5.required' => 'No seleccionó el asesor para el turno del miercoles en la tarde',
            'u6.required' => 'No seleccionó el asesor para el turno del jueves en la mañana',
            'u7.required' => 'No seleccionó el asesor para el turno del viernes en la mañana',
            'u8.required' => 'No seleccionó el asesor para el turno del sábado en la mañana',
            'u9.required' => 'No seleccionó el asesor para el turno del viernes en la tarde',
            'u10.required' => 'No seleccionó el asesor para el turno del sábado en la tarde',
        ]);
        //dd($fechas, $turno);
        
        $id = $turno->id;
        for ($i = 0; $i < 11; $i++) {
            $turno = Turno::findOrFail($id+$i);
//            $data['turno'] = new Carbon($fechas['f'.$i] . ':00:00');
            $data['user_id'] = $fechas['u'.$i];
            if ($data['user_id'] != $turno->user_id) {
                $data['user_actualizo'] = Auth::user()->id;
                //dd($turno, $data);
                $turno->update($data);
            }
        }
        //dd($fechas, $data, $turno);

        $semana = $fechas['semana'];
        if ('' == $semana or is_null($semana)) {
            //return redirect()->route('turnos');
            return redirect()->back();
        } else {
            return redirect()->route('turnos.crear', $semana);
        }
    }

    public function editarTurno(Turno $turno, $id)
    {
        $user_ant = $turno->user_id . ' => ' . $turno->user->name;
        $data['user_id'] = $id;
        //dd($turno, $data, $id);
        $turno->update($data);
        //dd($turno);
        $nombre = Turno::findOrFail($turno->id)->user->name;  // Recupera el turno desde la bd.

        Bitacora::create([
            'user_id' => Auth::user()->id,
            'tx_modelo' => 'Turno',
            'tx_data' => $turno->id . ' ==>>> ' . $user_ant . ' | ' . $turno->user_id .
                        ' => ' . $nombre,
            'tx_tipo' => 'A',
	    'tx_host' => $_SERVER['REMOTE_ADDR']
        ]);

        //return redirect()->route('turnos');
        return redirect()->back();
    }

    public function destroy(Turno $turno)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        if ((!(Auth::user()->is_admin)) or (!(is_null($turno->user_borro)))) {
            return redirect()->back();
        }

        $datos = 'id:' . $turno->id . ', turno:' . $turno->turno . '.'; // Para bitacoras.

        $id = $turno->id;
        for ($i = 0; $i < 11; $i++) {
            $turno = Turno::findOrFail($id+$i);
            $data['user_borro'] = Auth::user()->id;
            $data['turno'] = $turno->turno->addHours(1);   // Evita duplicidad de indice unico en 'turno'.
            $turno->update($data);
            $turno->delete();
        }

        Bitacora::create([
            'user_id' => Auth::user()->id,
            'tx_modelo' => 'Turno',
            'tx_data' => $datos,
            'tx_tipo' => 'B',
	        'tx_host' => $_SERVER['REMOTE_ADDR']
        ]);

        return redirect()->back();
    }

    public function correoTurnos()
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!Auth::user()->is_admin) {
            return redirect()->back();
        }

        $host = env('MAIL_HOST');
        $correo = 'N';
        if (!($ip = gethostbyname($host)) or ($ip == $host)) { // No hay conexon a Internet.
            if (1 == $ruta)
                return redirect()->route('turnos', ['correo' => $correo]);
            elseif (2 == $ruta)         // Nunca deberia suceder porque 'show' no existe.
                return redirect()->route('turnos',     // La ruta 'show' no existe.
                                ['correo' => $correo]);
            else return $correo;
        }

        //return new TurnosAsesor(User::findOrFail(2));  // Vista preliminar del correo, en el navegador.
        $usersIds = Turno::select(DB::raw("distinct user_id")) // Devuelve arreglo de Turno.
                            ->where('turno', '>', now())    // Laravel asume 'UTC' para todas las columnas 'date' de la BdD.
//                            ->where('turno', '>', now(Fecha::$ZONA))
                            ->get();
        if ($usersIds->isEmpty()) {
            return redirect()->route('turnos', ['correo' => $correo]);
        }
        foreach ($usersIds as $userId) {
            $user = User::findOrFail($userId)->first(); // find solo me devuelve un arreglo.
            //dd($user);
            $turnos = $user->turnos
                            ->where('turno', '>', now());   // Laravel asume 'UTC' para todas las columnas 'date' de la BdD.
            //dd($turnos);
            Mail::to($user->email, $user->name) // $user es un objeto Turno. Ver arriba.
                    ->send(new TurnosAsesor($user, $turnos));
        }
        return redirect()->route('turnos', ['correo' => 's']);
    } // public function correoTurnos()

    public static function correoTurnosSemanaPasada()
    {
        $host = env('MAIL_HOST');
        if (!($ip = gethostbyname($host)) or ($ip == $host)) { // No hay conexon a Internet.
            return;
        }

        $periodo['periodo'] = 'semana_pasada';
        list($fecha_desde, $fecha_hasta) = Fecha::periodo($periodo);
        $turnos = Turno::whereBetween('turno', [$fecha_desde, $fecha_hasta])->get();    // "whereBetween" en es metodo de Eloquent.
        $turnos = $turnos->where('tarde', '!=', '');    // 'where' es un metodo de la Collection.
        if ($turnos->isEmpty()) return;
        $correoGerente = User::CORREO_GERENTE;
        Mail::to($correoGerente)
                ->send(new TurnosErradosSemanaPasada($turnos));
        return;
    } // function correoTurnosSemanaPasada()

    public static function actualizarAviso()
    {
        $ayer = Fecha::ayer();
        $manana = Fecha::manana();
        $turnos = Turno::whereBetween('turno', [$ayer, $manana])
                        ->whereNull('llegada')
                        ->whereNotIn('id', [DB::raw("SELECT turno_id FROM avisos WHERE turno_id IS NOT NULL")])
                        ->get();
        foreach ($turnos as $turno) {
            Aviso::create([
                'user_id' => $turno->user_id,
                'turno_id' => $turno->id,
                'tipo' => 'C',
                'fecha' => $turno->turno,
                //'descripcion' => 'No se conecto en su turno de la ' . $turno->fec_tur,
                'descripcion' => $turno->descripcion,
                'user_creo' => $turno->user_creo
            ]);
        }
        return;
    }
}
