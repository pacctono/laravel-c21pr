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
    protected $tipo = 'Reporte';

    public function index($muestra = 'Asesor')
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        if (!(Auth::user()->is_admin)) {
            return redirect()->back();
        }
        
        if ('POST' == request()->method()) {
            $fechas = request()->all();
            $muestra = session('muestra', 'Asesor');
            list ($fecha_desde, $fecha_hasta) = Fecha::periodo($fechas);
        } elseif ('Conexion' == $muestra) {
            $fecha_desde = (new Carbon(Bitacora::min('created_at')))
                                ->timezone('America/Caracas')->startOfDay();
            $fecha_hasta = (new Carbon(Bitacora::max('created_at')))
                                ->timezone('America/Caracas')->endOfDay();
        } else {
            $fecha_desde = (new Carbon(Contacto::min('created_at')))
                                ->timezone('America/Caracas')->startOfDay();
            $fecha_hasta = (new Carbon(Contacto::max('created_at')))
                                ->timezone('America/Caracas')->endOfDay();
        }

        $title = $this->tipo . ' por ' . $muestra . ' desde ' . $fecha_desde->format('d/m/Y') .
                                                    ' hasta ' . $fecha_hasta->format('d/m/Y');

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
            default:        // 'Fecha'
                $elemsRep = Contacto::contactosXFecha($fecha_desde, $fecha_hasta);
        } 
        $elemsRep = $elemsRep->get();

        session(['fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta, 'muestra' => $muestra]);
        return view('reportes.index', compact('title', 'elemsRep', 'chart', 'muestra',
                                                'fecha_desde', 'fecha_hasta'));
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

        if (!(Auth::user()->is_admin)) {
            return redirect()->back();
        }

        if ('POST' == request()->method()) {
            $fechas = request()->all();
            $muestra = session('muestra', 'Asesor');
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

        $title = $this->tipo . ' por ' . $muestra . ' desde ' . $fecha_desde->format('d/m/Y') .
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
            default:        // 'Fecha'
                $elemsRep = Contacto::contactosXFecha($fecha_desde, $fecha_hasta);
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
            } else {
                $arrEtiq[]  = substr($elemento->name, 0, 20);
            }
            $arrData[]  = $elemento->atendidos;
            $strColor   = str_pad(dechex($hexColor), 6, '0', STR_PAD_LEFT);
            $arrColor[] = '#' . $strColor;
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

        session(['fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta, 'muestra' => $muestra]);
        return view('reportes.chart', compact('title', 'elemsRep', 'chart', 'tipo', 'muestra',
                                                'fecha_desde', 'fecha_hasta'));
    }
}
