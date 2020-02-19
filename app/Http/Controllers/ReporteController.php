<?php

namespace App\Http\Controllers;

use App\Contacto;
use App\VistaCliente;
use App\Propiedad;
use App\Cliente;
use App\Deseo;
use App\Origen;
use App\Precio;
use App\Tipo;
use App\Resultado;
use App\Zona;
use App\Caracteristica;
use App\Ciudad;
use App\Estado;
use App\Municipio;
use App\Venezueladdn;
use App\Turno;
use App\Bitacora;
use App\User;
use App\Charts\SampleChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;                 // PC
use App\MisClases\Fecha;
use App\MisClases\General;               // PC

class ReporteController extends Controller
{
    protected $tipo   = 'Reporte';
    protected $subTitulo1 = 'Contactos atendidos por ';
    protected $titulo = 'Listado de contactos iniciales ';
    protected $titProp = 'Listado de propiedades ';
    protected $lineasXPagina = General::LINEASXPAGINA;      // Constante en App\MisClases\General

    protected function prepararFechas($fecha, $asesor=0, $muestra=null)
    {
        $ZONA = Fecha::$ZONA;
        $estadisticaPropiedad = General::estadisticaPropiedad;  // Constante en App\MisClases\General

        //dd(request()->method(), $muestra, $fecha, $asesor);
        if ('POST' == request()->method()) {
            $fechas = request()->all();
            $muestra = session('muestra', 'Asesor');
            if (array_key_exists('asesor', $fechas)) $asesor = $fechas['asesor'];
            list ($fecha_desde, $fecha_hasta) = Fecha::periodo($fechas);
            //dd('0', $muestra, $fecha, $fecha_desde, $fecha_hasta, $asesor);
        } elseif (is_null($muestra)) {
            if ('' != session('fecha_desde', '') and '' != session('fecha_hasta', ''))  {   // chart
                $fecha_desde = new Carbon(session('fecha_desde'));
                $fecha_hasta = new Carbon(session('fecha_hasta'));
                $asesor = session('asesor');
                $muestra = session('muestra', 'Asesor');
            } else {          // Esto nunca deberia suceder.
                $muestra = 'Lados';
                $fecha_desde = null;
                $fecha_hasta = null;
            }
            //dd('1', $muestra, $fecha, $fecha_desde, $fecha_hasta, $asesor);
        } elseif ('Conexion' == $muestra) {
            $fecha_desde = (new Carbon(Bitacora::min('created_at', $ZONA)))->startOfDay();
            $fecha_hasta = (new Carbon(Bitacora::max('created_at', $ZONA)))->endOfDay();
        } elseif ('Cumpleanos' == $muestra) {
            $fecha_desde = Fecha::hoy();
            $fecha_hasta = Fecha::hoy()->addDays(30)->endOfDay();
            //dd('2', $muestra, $fecha, $fecha_desde, $fecha_hasta, $asesor);
        } elseif (array_key_exists($muestra , $estadisticaPropiedad)) {
            $fecha_desde = (new Carbon(Propiedad::min($fecha, $ZONA)))->startOfDay();
            $fecha_hasta = (new Carbon(Propiedad::max($fecha, $ZONA)))->endOfDay();
            $asesor = 0;
            //dd('3', $muestra, $fecha, $fecha_desde, $fecha_hasta, $asesor);
        } else {                                        // Se trabaja con Contactos
            $fecha_desde = (new Carbon(Contacto::min('created_at', $ZONA)))->startOfDay();
            $fecha_hasta = (new Carbon(Contacto::max('created_at', $ZONA)))->endOfDay();
            $asesor = 0;
            //dd('4', $muestra, $fecha, $fecha_desde, $fecha_hasta, $asesor);
        }
        //dd('5', $muestra, $fecha, $fecha_desde, $fecha_hasta, $asesor);

        return array($muestra, $fecha_desde, $fecha_hasta, $asesor);
    }
    protected function title($muestra, $fecha, $fecha_desde, $fecha_hasta,
                                $asesor=0, $modelo='App\\Propiedad')
    {
        $ZONA = Fecha::$ZONA;

        if ('Conexion' == $muestra)
            $subTitulo1 = 'Conexiones por ';
        elseif ('Cumpleanos' == $muestra)
            $subTitulo1 = '';
        elseif (('Lados' == $muestra) or
                  ('LadMes' == $muestra))
            $subTitulo1 = 'Lados por ';
        elseif ('Negociaciones' == $muestra)
            $subTitulo1 = 'Negociaciones por ';
        elseif (('Comision' == $muestra) or
                  ('ComMes' == $muestra))
            $subTitulo1 = 'Comisiones por ';
        else $subTitulo1 = $this->subTitulo1;
        if (('Asesor' == $muestra) or
            ('Conexion' == $muestra) or
            ('Comision' == $muestra) or
            ('Lados' == $muestra))
            $subTitulo2 = 'Asesor ';
        elseif ('Fecha' == $muestra) {
            if (0 < $asesor) $subTitulo2 = ' de ' . User::find($asesor)->name . ' ';
            else $subTitulo2 = $muestra;
        }
        elseif (('ComMes' == $muestra) or
                ('LadMes' == $muestra) or
                ('Negociaciones' == $muestra))
            $subTitulo2 = 'Mes ';
        else $subTitulo2 = $muestra;

        return $subTitulo1 . $subTitulo2 . ' desde ' .
                 ((is_null($fecha_desde))?
                    (new Carbon($modelo::min($fecha, $ZONA)))->startOfDay()->format('d/m/Y'):
                    $fecha_desde->format('d/m/Y'))
                   . ' hasta ' .
                 ((is_null($fecha_hasta))?
                    (new Carbon($modelo::max($fecha, $ZONA)))->endOfDay()->format('d/m/Y'):
                    $fecha_hasta->format('d/m/Y'));

    }
    protected function elemsReporte($muestra, $fecha, $fecha_desde, $fecha_hasta, $asesor=0)
    {
        switch ($muestra) {
            case 'Asesor':
                $elemsRep = User::contactosXAsesor($fecha_desde, $fecha_hasta);
                break;
            case 'Conexion':
                $elemsRep = User::conexionXAsesor($fecha_desde, $fecha_hasta);
                break;
            case 'Cumpleanos':
                $elemsRep = User::cumpleanos($fecha_desde, $fecha_hasta);
                break;
            case 'Origen':
                $elemsRep = Origen::contactosXOrigen($fecha_desde, $fecha_hasta);
                break;
            case 'Fecha':
                $elemsRep = Contacto::contactosXFecha($fecha_desde, $fecha_hasta, $asesor);
                break;
            case 'Lados':
                $elemsRep = User::ladosXAsesor($fecha, $fecha_desde, $fecha_hasta);
                break;
            case 'Comision':
                $elemsRep = User::where('id', '>', 1);
                break;
            case 'Negociaciones':
                $elemsRep = Propiedad::negociacionesXMes($fecha, $fecha_desde, $fecha_hasta, $asesor);
                break;
            case 'LadMes':
                $elemsRep = Propiedad::ladosXMes($fecha, $fecha_desde, $fecha_hasta, $asesor);
                break;
            case 'ComMes':
                $elemsRep = Propiedad::comisionXMes($fecha, $fecha_desde, $fecha_hasta, $asesor);
                break;
            default:        // 'Fecha'
                dd(request()->method(), session('muestra', 'muestra sin asignar'), session('asesor', 'asesor sin asignar'));
                $elemsRep = Contacto::contactosXFecha($fecha_desde, $fecha_hasta, $asesor);
        } 
        if ('ComMes' != $muestra) $elemsRep = $elemsRep->get();

        return $elemsRep;
    }   // elemsReporte($muestra, $fecha, $fecha_desde, $fecha_hasta, $asesor=0)
    public function index($muestra='Asesor', $accion='html')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!(Auth::user()->is_admin) and (('Fecha' != $muestra))) {
            return redirect()->back();
        }
        $dato = request()->all();
        $ZONA = Fecha::$ZONA;
        //dd(request()->method(), count($dato), $dato);
        if (Auth::user()->is_admin) {
            $asesor = session('asesor', 0);
            $users  = User::all()->where('id', '>', 1); // Todos los usuarios, excepto id=1.
        } else {
            $asesor = Auth::user()->id;
        }

        $fecha = 'fecha_firma';
        list($muestra, $fecha_desde, $fecha_hasta, $asesor) =
                    $this->prepararFechas($fecha, $asesor, $muestra);   // $muestra es obligatoria.
        //dd($muestra, $fecha, $fecha_desde, $fecha_hasta, $asesor);
        $estadisticaPropiedad = General::estadisticaPropiedad;  // Constante en App\MisClases\General
        if (array_key_exists($muestra , $estadisticaPropiedad))         // Se trabaja con Propiedades
            $modelo = 'App\\Propiedad';
//        elseif (array_key_exists($muestra , $estadisticaContacto))    // Se trabaja con Contactos
        else $modelo = 'App\\Contacto';
        $title = $this->title($muestra, $fecha, $fecha_desde, $fecha_hasta, $asesor, $modelo);
        //dd($muestra, $fecha, $fecha_desde, $fecha_hasta, $asesor);
        $hoy = Fecha::hoy()->format('d-m');
        $elemsRep = $this->elemsReporte($muestra, $fecha, $fecha_desde, $fecha_hasta, $asesor);

        if (is_null($fecha_desde))
            $fecha_desde = (new Carbon(Propiedad::min($fecha, $ZONA)))->startOfDay();
        if (is_null($fecha_hasta))
            $fecha_hasta = (new Carbon(Propiedad::max($fecha, $ZONA)))->endOfDay();
        //dd($muestra, $fecha, $fecha_desde, $fecha_hasta, $asesor);

        $fecha_desde = $fecha_desde->format('Y-m-d');
        $fecha_hasta = $fecha_hasta->format('Y-m-d');

        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
        session(['fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta,
                    'muestra' => $muestra, 'asesor' => $asesor]);
        //dd($muestra, $elemsRep, $fecha_desde, $fecha_hasta, $asesor);
        if ('html' == $accion)
            return view('reportes.index',
                    compact('title', 'users', 'elemsRep', 'chart',
                            'muestra', 'fecha_desde', 'fecha_hasta',
                            'asesor', 'hoy', 'movil', 'accion'));
        $html = view('reportes.index',
                    compact('title', 'users', 'elemsRep', 'chart',
                            'muestra', 'fecha_desde', 'fecha_hasta',
                            'asesor', 'hoy', 'movil', 'accion'))
                ->render();
        General::generarPdf($html, $muestra, $accion);
    }   // index($muestra='Asesor', $accion='html')
/**
 *  There are a few methods you can use in all datasets (regardless of the type, or charting library).
 *  These includes:
 *
 *  type(string $type) - Set the dataset type.
 *  values($values) - Set the dataset values.
 *  options($options, bool $overwrite = false) - Set the dataset options.
 */
    public function chart($tipo='line', $accion='html')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        if (Auth::user()->is_admin) {
            $asesor = session('asesor', 0);
            $users  = User::all()->where('id', '>', 1);         // Todos los usuarios.
        } else {
            $asesor = Auth::user()->id;
        }

        $fecha = 'fecha_firma';
        list($muestra, $fecha_desde, $fecha_hasta, $asesor) =
                                        $this->prepararFechas($fecha, $asesor);
        if (!isset($muestra)) $muestra = 'Asesor';

        if (!(Auth::user()->is_admin) and (('Fecha' != $muestra))) {
            return redirect()->back();
        }

        $estadisticaPropiedad = General::estadisticaPropiedad;  // Constante en App\MisClases\General
        if (array_key_exists($muestra , $estadisticaPropiedad))         // Se trabaja con Propiedades
            $modelo = 'App\\Propiedad';
//        elseif (array_key_exists($muestra , $estadisticaContacto))    // Se trabaja con Contactos
        else $modelo = 'App\\Contacto';
        $title = $this->title($muestra, $fecha, $fecha_desde, $fecha_hasta, $asesor, $modelo);
        $legenda = $title;

        if ('' == $tipo or is_null($tipo)) {
            $tipo = 'line';
        }

        $chart = new SampleChart;

        $elemsRep = $this->elemsReporte($muestra, $fecha, $fecha_desde, $fecha_hasta, $asesor);

// Add the dataset (we will go with the chart template approach)
        $arrEtiq  = array();
        $arrData  = array();
        $arrColor = array();

        $intervalo = 0xffffff/count($elemsRep);
        $hexColor  = 0x0;
        foreach ($elemsRep as $elemento) {
            if ('Fecha' == $muestra) {
                $arrEtiq[]  = $elemento->fechaContacto;
            } elseif ('Origen' == $muestra) {
                $arrEtiq[]  = $elemento->descripcion;
            } elseif (('Negociaciones' == $muestra) or
                      ('ComMes' == $muestra) or
                      ('LadMes' == $muestra)) {
                if (('' == $elemento->agno) || ('' == $elemento->mes))
                    $arrEtiq[] = 'Fecha no suministrada';
                else
                    $arrEtiq[] = $elemento->agno . '-' . $elemento->mes;
            } else {
                $arrEtiq[]  = substr($elemento->name, 0, 20);
            }
            if ('Lados' == $muestra) {
                $arrData[] = $elemento->captadas + $elemento->cerradas;
            } elseif ('Comision' == $muestra) {
                $arrData[] = $elemento->comision;
            } elseif ('Negociaciones' == $muestra) {
                $arrData[] = $elemento->negociaciones;
            } elseif ('LadMes' == $muestra) {
                $arrData[] = $elemento->lados;
            } elseif ('ComMes' == $muestra) {
                $arrData[] = $elemento->comision;
            } else {
                $arrData[]  = $elemento->atendidos;
            }
            $strColor   = str_pad(dechex($hexColor), 6, '0', STR_PAD_LEFT);
            $arrColor[] = '#' . $strColor;
//            dd($elemsRep, $hexColor, dechex($intervalo));
            $hexColor  += $intervalo;
        }
//        dd($arrColor);
        $lineaColor = '#ffffff';
        if ('line'==$tipo) {
            $arrColor = '#ffffff';
            $lineaColor = '#000000';
        }
        $chart->displayLegend('pie'==$tipo);
        $chart->labels($arrEtiq);
        $chart->displayAxes('pie'!=$tipo);
        $chart->barWidth(1);
        $chart->title($title);
        $chart->height(400);
        if ('html' == $accion) $chart->width(1000);
        else $chart->width(0);  // 0/null: auto
        $chart->dataset($legenda, $tipo, $arrData)     // bar, pie, line, ...
            ->backgroundColor($arrColor)
            ->color($lineaColor)
            ->dashed([1, 5])                        // [0], por defecto.
            ->lineTension(0);                       // 0.5, por defecto.
//            ->color('#ff0000');                 // dataset configuration presets.

        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
        session(['fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta,
                    'muestra' => $muestra, 'asesor' => $asesor]);
        if ('html' == $accion)
            return view('reportes.chart',
                    compact('title', 'users', 'elemsRep', 'chart', 'tipo', 'muestra',
                            'fecha_desde', 'fecha_hasta', 'asesor', 'movil', 'accion'));
        $html = view('reportes.chart',
                    compact('title', 'users', 'elemsRep', 'chart', 'tipo', 'muestra',
                            'fecha_desde', 'fecha_hasta', 'asesor', 'movil', 'accion'));
        General::generarPdf($html, $muestra, $accion);
    }

    public function contactosXUser($id = 0, $orden = 'id')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!(Auth::user()->is_admin)) {
            return redirect()->back();
        }
        if ((0 == $id) or (is_null($id))) {
            return redirect()->back();
        }

        $ruta = request()->path();  // reportes/contactosUser/[id]/id; 18: "User..."
        $tipo = strtolower(substr($ruta, 18, strpos($ruta, '/', 18)-18));   // "user"
        $title = $this->titulo . 'y clientes del ' . 'asesor' . ': ' .
                    User::findOrFail($id)->name;

        if ('' == $orden or is_null($orden)) {
            $orden = 'id';
        }
        $tipoId   = $tipo . '_id';      // $tipo = 'user', $tipoId = 'user_id'.
        $contactos = VistaCliente::where($tipoId, $id)->orderBy($orden)		// Para mantener la herencia de los contactos.
                                ->paginate($this->lineasXPagina);

	    $rutRetorno = 'reporte.contactos' . ucfirst($tipo);
	    $tipo .= 's';						// route 'users'
        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
        return view('reportes.contactos', compact('title', 'contactos', 'tipo',
                                                    'rutRetorno', 'id', 'movil'));
    }
/*
    public function contactosXDeseo($id = 0, $orden = 'id')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!(Auth::user()->is_admin)) {
            return redirect()->back();
        }
        if ((0 == $id) or (is_null($id))) {
            return redirect()->back();
        }

        $ruta = request()->path();
        $tipo = strtolower(substr($ruta, 18, strpos($ruta, '/', 18)-18));
        $title = $this->titulo . 'con el ' . $tipo . ': ' . Deseo::find($id)->descripcion;

        if ('' == $orden or is_null($orden)) {
            $orden = 'id';
        }
        $tipoId   = $tipo . '_id';
        $contactos = Contacto::where($tipoId, $id)->orderBy($orden)->paginate(10);

	    $rutRetorno = 'reporte.contactos' . ucfirst($tipo);
        return view('reportes.contactos', compact('title', 'contactos', 'tipo', 'rutRetorno', 'id'));
    }

    public function contactosXTipo($id = 0, $orden = 'id')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!(Auth::user()->is_admin)) {
            return redirect()->back();
        }
        if ((0 == $id) or is_null($id)) {
            return redirect()->back();
        }

        $ruta = request()->path();
        $tipo = strtolower(substr($ruta, 18, strpos($ruta, '/', 18)-18));
        $title = $this->titulo . 'con la ' . $tipo . ': ' . Tipo::find($id)->descripcion;

        if ('' == $orden or is_null($orden)) {
            $orden = 'id';
        }
	    $tipoId = $tipo . '_id';
        $contactos = Contacto::where($tipoId, $id)->orderBy($orden)->paginate(10);

	    $rutRetorno = 'reporte.contactos' . ucfirst($tipo);
        return view('reportes.contactos', compact('title', 'contactos', 'tipo', 'rutRetorno', 'id'));
    }

    public function contactosXZona($id = 0, $orden = 'id')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!(Auth::user()->is_admin)) {
            return redirect()->back();
        }
        if ((0 == $id) or is_null($id)) {
            return redirect()->back();
        }

        $ruta = request()->path();
        $tipo = strtolower(substr($ruta, 18, strpos($ruta, '/', 18)-18));
        $title = $this->titulo . 'en la ' . $tipo . ': ' . Zona::find($id)->descripcion;

        if ('' == $orden or is_null($orden)) {
            $orden = 'id';
        }
	    $tipoId = $tipo . '_id';
        $contactos = Contacto::where($tipoId, $id)->orderBy($orden)->paginate(10);

	    $rutRetorno = 'reporte.contactos' . ucfirst($tipo);
        return view('reportes.contactos', compact('title', 'contactos', 'tipo', 'rutRetorno', 'id'));
    }

    public function contactosXPrecio($id = 0, $orden = 'id')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!(Auth::user()->is_admin)) {
            return redirect()->back();
        }
        if ((0 == $id) or is_null($id)) {
            return redirect()->back();
        }

        $ruta = request()->path();
        $tipo = strtolower(substr($ruta, 18, strpos($ruta, '/', 18)-18));
        $title = $this->titulo . 'con el ' . $tipo . ': ' . Precio::find($id)->descripcion;

        if ('' == $orden or is_null($orden)) {
            $orden = 'id';
        }
	    $tipoId = $tipo . '_id';
        $contactos = Contacto::where($tipoId, $id)->orderBy($orden)->paginate(10);

	    $rutRetorno = 'reporte.contactos' . ucfirst($tipo);
        return view('reportes.contactos', compact('title', 'contactos', 'tipo', 'rutRetorno', 'id'));
    }

    public function contactosXOrigen($id = 0, $orden = 'id')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!(Auth::user()->is_admin)) {
            return redirect()->back();
        }
        if ((0 == $id) or is_null($id)) {
            return redirect()->back();
        }

        $ruta = request()->path();
        $tipo = strtolower(substr($ruta, 18, strpos($ruta, '/', 18)-18));
        $title = $this->titulo . 'con ' . $tipo . ': ' . Origen::find($id)->descripcion;

        if ('' == $orden or is_null($orden)) {
            $orden = 'id';
        }
	    $tipoId = $tipo . '_id';
        $contactos = Contacto::where($tipoId, $id)->orderBy($orden)->paginate(10);

	    $rutRetorno = 'reporte.contactos' . ucfirst($tipo);
        return view('reportes.contactos', compact('title', 'contactos', 'tipo', 'rutRetorno', 'id'));
    }

    public function contactosXResultado($id = 0, $orden = 'id')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!(Auth::user()->is_admin)) {
            return redirect()->back();
        }
        if ((0 == $id) or is_null($id)) {
            return redirect()->back();
        }

        $ruta = request()->path();
        $tipo = strtolower(substr($ruta, 18, strpos($ruta, '/', 18)-18));
        $title = $this->titulo . 'con el ' . $tipo . ': ' . Resultado::find($id)->descripcion;

        if ('' == $orden or is_null($orden)) {
            $orden = 'id';
        }
	    $tipoId = $tipo . '_id';
        $contactos = Contacto::where($tipoId, $id)->orderBy($orden)->paginate(10);

	    $rutRetorno = 'reporte.contactos' . ucfirst($tipo);
        return view('reportes.contactos', compact('title', 'contactos', 'tipo', 'rutRetorno', 'id'));
    }*/

    public function contactosX($id = 0, $orden = 'id')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!(Auth::user()->is_admin)) {
            return redirect()->back();
        }
        if ((0 == $id) or is_null($id)) {
            return redirect()->back();
        }

        $ruta = request()->path();
        $tipo = strtolower(substr($ruta, 18, strpos($ruta, '/', 18)-18));
	    $modelo = 'App\\' . ucfirst($tipo);
        $title = $this->titulo . 'con el ' . $tipo . ': ' . $modelo::find($id)->descripcion;

        if (('' == $orden) or is_null($orden)) {
            $orden = 'id';
        }
	    $tipoId = $tipo . '_id';
        $contactos = Contacto::where($tipoId, $id)->orderBy($orden)->paginate($this->lineasXPagina);

	    $rutRetorno = 'reporte.contactos' . substr($modelo, 4);
        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
        return view('reportes.contactos', compact('title', 'contactos', 'tipo', 'rutRetorno', 'id', 'movil'));
    }

    public function propiedadesX($id = 0, $orden = 'id')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!(Auth::user()->is_admin)) {
            return redirect()->back();
        }
        if ((0 == $id) or is_null($id)) {
            return redirect()->back();
        }

        $ruta = request()->path();
        $tipo = strtolower(substr($ruta, 20, strpos($ruta, '/', 20)-20));   // Los primeros 20 caracteres de $ruta: 'reportes/propiedades'.
	    $modelo = 'App\\' . ucfirst($tipo);
        $title = $this->titProp;
        if ('user' != $tipo)
            $title .= 'con ' . $tipo . ': ' . $modelo::findOrFail($id)->descripcion;
        else $title .= ' del asesor: ' . User::findOrFail($id)->name;

        if ('' == $orden or is_null($orden)) {
            $orden = 'id';
        }
	    $tipoId = $tipo . '_id';
        $propiedades = Propiedad::where($tipoId, $id)->orderBy($orden);

	    $rutRetorno = 'reporte.propiedades' . substr($modelo, 4);
        $agente = new Agent();
        $movil  = $agente->isMobile() and true;             // Fuerzo booleana. No funciona al usar el metodo directamente.
        list ($filas, $tPrecio, $tLados, $tCompartidoConIva, $tFranquiciaSinIva,
                $tFranquiciaConIva, $tFranquiciaPagarR, $tRegalia, $tSanaf5PorCiento,
                $tOficinaBrutoReal, $tBaseHonorariosSo, $tBaseParaHonorari,
                $tCaptadorPrbr, $tGerente, $tCerradorPrbr, $tBonificaciones,
                $tComisionBancaria, $tIngresoNetoOfici, $tPrecioVentaReal, $tPuntos,
                $tCaptadorPrbrSel, $tCerradorPrbrSel, $tLadosCap, $tLadosCer,
                $tPvrCaptadorPrbrSel, $tPvrCerradorPrbrSel,
                $tPuntosCaptador, $tPuntosCerrador) =
                Propiedad::totales($propiedades);
        $propiedades = $propiedades->paginate($this->lineasXPagina);
        //session(['orden' => $orden]);
        return view('reportes.propiedades', compact('title', 'propiedades',
                                'filas', 'tPrecio', 'tCompartidoConIva', 'tLados',
                                'tFranquiciaSinIva', 'tFranquiciaConIva', 'tFranquiciaPagarR',
                                'tRegalia', 'tSanaf5PorCiento', 'tOficinaBrutoReal',
                                'tBaseHonorariosSo', 'tBaseParaHonorari', 'tIngresoNetoOfici',
                                'tCaptadorPrbr', 'tGerente', 'tCerradorPrbr', 'tBonificaciones',
                                'tComisionBancaria', 'tPrecioVentaReal', 'tPuntos',
                                'tCaptadorPrbrSel', 'tCerradorPrbrSel', 'tLadosCap', 'tLadosCer',
                                'tPvrCaptadorPrbrSel', 'tPvrCerradorPrbrSel',
                                'tPuntosCaptador', 'tPuntosCerrador',
                                'tipo', 'rutRetorno', 'id', 'movil'));
    }
}
