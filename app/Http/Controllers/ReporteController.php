<?php

namespace App\Http\Controllers;

use App\Contacto;
use App\Deseo;
use App\Origen;
use App\Precio;
use App\Propiedad;
use App\Resultado;
use App\Zona;
use App\Venezueladdn;
use App\Turno;
use App\Bitacora;
use App\User;
use App\Charts\SampleChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\MisClases\Fecha;

class ReporteController extends Controller
{
    protected $tipo   = 'Reporte';
    protected $titulo = 'Listado de contactos iniciales ';

    public function index($muestra = 'Asesor')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!(Auth::user()->is_admin) and (('Asesor' == $muestra) or ('Conexion' == $muestra))) {
            return redirect()->back();
        }
        $ZONA = Fecha::$ZONA;
        
        if (Auth::user()->is_admin) {
            $asesor = session('asesor', 0);
            $users  = User::all()->where('id', '>', 1); // Todos los usuarios, excepto id=1.
        } else {
            $asesor = Auth::user()->id;
        }
        if ('POST' == request()->method()) {
            $fechas = request()->all();
            $muestra = session('muestra', 'Asesor');
            if (array_key_exists('asesor', $fechas)) $asesor = $fechas['asesor'];
            list ($fecha_desde, $fecha_hasta) = Fecha::periodo($fechas);
        } elseif ('Conexion' == $muestra) {
            $fecha_desde = (new Carbon(Bitacora::min('created_at', $ZONA)))->startOfDay();
            $fecha_hasta = (new Carbon(Bitacora::max('created_at', $ZONA)))->endOfDay();
        } elseif ('Cumpleanos' == $muestra) {
            $fecha_desde = Fecha::hoy();
            $fecha_hasta = Fecha::hoy()->addDays(30)->endOfDay();
        } else {
            $fecha_desde = (new Carbon(Contacto::min('created_at', $ZONA)))->startOfDay();
            $fecha_hasta = (new Carbon(Contacto::max('created_at', $ZONA)))->endOfDay();
        }

        $title = $this->tipo . ' por ' . $muestra .
                                    ((('Fecha'==$muestra) and (0<$asesor))?
                                            (' de '.User::find($asesor)->name.' '):'') .
                                    ' desde ' . $fecha_desde->format('d/m/Y') .
                                    ' hasta ' . $fecha_hasta->format('d/m/Y');

        $hoy = Fecha::hoy()->format('d-m');
        switch ($muestra) {
            case 'Asesor':
/*            $elemsRep = Contacto::select('user_id', DB::raw('count(*) as atendidos'))
                                        ->whereBetween('created_at', [$fecha_desde, $fecha_hasta])
                                        ->groupBy('user_id')->get();*/
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
            default:        // 'Fecha'
                dd(request()->method(), session('muestra', 'muestra sin asignar'), session('asesor', 'asesor sin asignar'));
                $elemsRep = Contacto::contactosXFecha($fecha_desde, $fecha_hasta, $asesor);
        } 
        $elemsRep = $elemsRep->get();

        session(['fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta,
                    'muestra' => $muestra, 'asesor' => $asesor]);
        return view('reportes.index', compact('title', 'users', 'elemsRep', 'chart', 'muestra',
                                            'fecha_desde', 'fecha_hasta', 'asesor', 'hoy'));
    }
/**
 *  There are a few methods you can use in all datasets (regardless of the type, or charting library).
 *  These includes:
 *
 *  type(string $type) - Set the dataset type.
 *  values($values) - Set the dataset values.
 *  options($options, bool $overwrite = false) - Set the dataset options.
 */
    public function chart($tipo = 'line')
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
        if ('POST' == request()->method()) {
            $fechas = request()->all();
            $muestra = session('muestra', 'Asesor');
            if (array_key_exists('asesor', $fechas)) $asesor = $fechas['asesor'];
            list ($fecha_desde, $fecha_hasta) = Fecha::periodo($fechas);
        } elseif ('' != session('fecha_desde', '') and '' != session('fecha_hasta', ''))  {
            $fecha_desde = session('fecha_desde');
            $fecha_hasta = session('fecha_hasta');
            $muestra = session('muestra', 'Asesor');
        } elseif ('Conexion' == $muestra) {
            $fecha_desde = (new Carbon(Bitacora::min('created_at')))->startOfDay();
            $fecha_hasta = (new Carbon(Bitacora::max('created_at')))->endOfDay();
        } else {
            $fecha_desde = (new Carbon(Contacto::min('created_at')))->startOfDay();
            $fecha_hasta = (new Carbon(Contacto::max('created_at')))->endOfDay();
            $muestra = 'Asesor';
        }

        if (!(Auth::user()->is_admin) and (('Asesor' == $muestra) or ('Conexion' == $muestra))) {
            return redirect()->back();
        }

        $title = $this->tipo . ' por ' . $muestra .
                                    ((('Fecha'==$muestra) and (0<$asesor))?
                                            (' de '.User::find($asesor)->name.' '):'') .
                                    ' desde ' . $fecha_desde->format('d/m/Y') .
                                    ' hasta ' . $fecha_hasta->format('d/m/Y');
        $legenda = $title;

        if ('' == $tipo or $tipo == null) {
            $tipo = 'line';
        }

        $chart = new SampleChart;

        switch ($muestra) {
            case 'Asesor':
                $elemsRep = User::contactosXAsesor($fecha_desde, $fecha_hasta);
                break;
            case 'Conexion':
                $elemsRep = User::conexionXAsesor($fecha_desde, $fecha_hasta);
                break;
            case 'Origen':
                $elemsRep = Origen::contactosXOrigen($fecha_desde, $fecha_hasta);
                break;
            case 'Fecha':
                $elemsRep = Contacto::contactosXFecha($fecha_desde, $fecha_hasta, $asesor);
                break;
            default:        // 'Fecha'
                dd(request()->method(), session('muestra', 'muestra sin asignar'), session('asesor', 'asesor sin asignar'));
                $elemsRep = Contacto::contactosXFecha($fecha_desde, $fecha_hasta, $asesor);
        } 
        $elemsRep = $elemsRep->get();

// Add the dataset (we will go with the chart template approach)
        $arrEtiq  = array();
        $arrData  = array();
        $arrColor = array();

        $intervalo = 0xffffff/count($elemsRep);
        $hexColor  = 0x0;
        foreach ($elemsRep as $elemento) {
            if ('Fecha' == $muestra) {
                $arrEtiq[]  = $elemento->fecha;
            } elseif ('Origen' == $muestra) {
                $arrEtiq[]  = $elemento->descripcion;
            } else {
                $arrEtiq[]  = substr($elemento->name, 0, 20);
            }
            $arrData[]  = $elemento->atendidos;
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
        $chart->width(1000);
        $chart->dataset($legenda, $tipo, $arrData)     // bar, pie, line, ...
            ->backgroundColor($arrColor)
            ->color($lineaColor)
            ->dashed([1, 5])                        // [0], por defecto.
            ->lineTension(0);                       // 0.5, por defecto.
//            ->color('#ff0000');                 // dataset configuration presets.

        session(['fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta,
                    'muestra' => $muestra, 'asesor' => $asesor]);
        return view('reportes.chart', compact('title', 'users', 'elemsRep', 'chart', 'tipo',
                                            'muestra', 'fecha_desde', 'fecha_hasta', 'asesor'));
    }

    public function contactosXUser($id = 0, $orden = 'id')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!(Auth::user()->is_admin)) {
            return redirect()->back();
        }
        if ((0 == $id) or (null == $id)) {
            return redirect()->back();
        }

        $ruta = request()->path();
        $tipo = strtolower(substr($ruta, 18, strpos($ruta, '/', 18)-18));
        $title = $this->titulo . 'del ' . 'asesor' . ': ' . User::find($id)->name;

        if ('' == $orden or $orden == null) {
            $orden = 'id';
        }
        $tipoId   = $tipo . '_id';
        $contactos = Contacto::where($tipoId, $id)->orderBy($orden)->paginate(10);

	    $rutRetorno = 'reporte.contactos' . ucfirst($tipo);
	    $tipo .= 's';						// route 'users'
        return view('reportes.contactos', compact('title', 'contactos', 'tipo', 'rutRetorno', 'id'));
    }

    public function contactosXDeseo($id = 0, $orden = 'id')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!(Auth::user()->is_admin)) {
            return redirect()->back();
        }
        if ((0 == $id) or (null == $id)) {
            return redirect()->back();
        }

        $ruta = request()->path();
        $tipo = strtolower(substr($ruta, 18, strpos($ruta, '/', 18)-18));
        $title = $this->titulo . 'con el ' . $tipo . ': ' . Deseo::find($id)->descripcion;

        if ('' == $orden or $orden == null) {
            $orden = 'id';
        }
        $tipoId   = $tipo . '_id';
        $contactos = Contacto::where($tipoId, $id)->orderBy($orden)->paginate(10);

	    $rutRetorno = 'reporte.contactos' . ucfirst($tipo);
        return view('reportes.contactos', compact('title', 'contactos', 'tipo', 'rutRetorno', 'id'));
    }

    public function contactosXPropiedad($id = 0, $orden = 'id')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!(Auth::user()->is_admin)) {
            return redirect()->back();
        }
        if ((0 == $id) or (null == $id)) {
            return redirect()->back();
        }

        $ruta = request()->path();
        $tipo = strtolower(substr($ruta, 18, strpos($ruta, '/', 18)-18));
        $title = $this->titulo . 'con la ' . $tipo . ': ' . Propiedad::find($id)->descripcion;

        if ('' == $orden or $orden == null) {
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
        if ((0 == $id) or (null == $id)) {
            return redirect()->back();
        }

        $ruta = request()->path();
        $tipo = strtolower(substr($ruta, 18, strpos($ruta, '/', 18)-18));
        $title = $this->titulo . 'en la ' . $tipo . ': ' . Zona::find($id)->descripcion;

        if ('' == $orden or $orden == null) {
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
        if ((0 == $id) or (null == $id)) {
            return redirect()->back();
        }

        $ruta = request()->path();
        $tipo = strtolower(substr($ruta, 18, strpos($ruta, '/', 18)-18));
        $title = $this->titulo . 'con el ' . $tipo . ': ' . Precio::find($id)->descripcion;

        if ('' == $orden or $orden == null) {
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
        if ((0 == $id) or (null == $id)) {
            return redirect()->back();
        }

        $ruta = request()->path();
        $tipo = strtolower(substr($ruta, 18, strpos($ruta, '/', 18)-18));
        $title = $this->titulo . 'con ' . $tipo . ': ' . Origen::find($id)->descripcion;

        if ('' == $orden or $orden == null) {
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
        if ((0 == $id) or (null == $id)) {
            return redirect()->back();
        }

        $ruta = request()->path();
        $tipo = strtolower(substr($ruta, 18, strpos($ruta, '/', 18)-18));
        $title = $this->titulo . 'con el ' . $tipo . ': ' . Resultado::find($id)->descripcion;

        if ('' == $orden or $orden == null) {
            $orden = 'id';
        }
	    $tipoId = $tipo . '_id';
        $contactos = Contacto::where($tipoId, $id)->orderBy($orden)->paginate(10);

	    $rutRetorno = 'reporte.contactos' . ucfirst($tipo);
        return view('reportes.contactos', compact('title', 'contactos', 'tipo', 'rutRetorno', 'id'));
    }
}
