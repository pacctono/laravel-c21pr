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
use App\User;
use App\Charts\SampleChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;        // PC
use Carbon\Carbon;                          // PC
use App\MisClases\Fecha;

class ReporteController extends Controller
{
    protected $diaSemana = [
        'Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'
    ];
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
        } else {
            $fecha_desde = (new Carbon(Contacto::min('created_at')))->startOfDay();
            $fecha_hasta = (new Carbon(Contacto::max('created_at')))->endOfDay();
        }

        $title = $this->tipo . ' por ' . $muestra . ' desde ' . $fecha_desde->format('d/m/Y') .
                                                    ' hasta ' . $fecha_hasta->format('d/m/Y');

        if ('Asesor' == $muestra) {
            $contactos = Contacto::select('user_id', DB::raw('count(*) as atendidos'))
                                        ->whereBetween('created_at', [$fecha_desde, $fecha_hasta])
                                        ->groupBy('user_id');
        } else {
            $contactos = Contacto::select(DB::raw('DATE_FORMAT(created_at, "%d/%m/%Y") as fecha'),
                                            DB::raw('count(*) as atendidos'))
                                        ->whereBetween('created_at', [$fecha_desde, $fecha_hasta])
                                        ->groupBy(DB::raw('DATE_FORMAT(created_at, "%d/%m/%Y")'))
                                        ->orderBy('created_at');
        }
        $contactos = $contactos->get();

        session(['fecha_desde' => $fecha_desde, 'fecha_hasta' => $fecha_hasta, 'muestra' => $muestra]);
        return view('reportes.index', compact('title', 'contactos', 'chart', 'muestra',
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

        if ('' != session('fecha_desde', '') and '' != session('fecha_hasta', ''))  {
            $fecha_desde = session('fecha_desde');
            $fecha_hasta = session('fecha_hasta');
            $muestra = session('muestra');
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

        if ('Asesor' == $muestra) {
            $contactos = Contacto::select('user_id', DB::raw('count(*) as atendidos'))
                                        ->whereBetween('created_at', [$fecha_desde, $fecha_hasta])
                                        ->groupBy('user_id');
        } else {
            $contactos = Contacto::select(DB::raw('DATE_FORMAT(created_at, "%d/%m/%Y") as fecha'),
                                            DB::raw('count(*) as atendidos'))
                                        ->whereBetween('created_at', [$fecha_desde, $fecha_hasta])
                                        ->groupBy(DB::raw('DATE_FORMAT(created_at, "%d/%m/%Y")'))
                                        ->orderBy('created_at');
        }
        $contactos = $contactos->get();

// Add the dataset (we will go with the chart template approach)
        $arrEtiq  = array();
        $arrData  = array();
        $arrColor = array();

        $intervalo = 0xffffff/count($contactos);
        $hexColor  = 0x0;
        foreach ($contactos as $contacto) {
            if ('Asesor' == $muestra) {
                $arrEtiq[]  = substr($contacto->user->name, 0, 20);
            } else {
                $arrEtiq[]  = $contacto->fecha;
            }
            $arrData[]  = $contacto->atendidos;
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
        $chart->dataset($legenda, $tipo, $arrData)     // bar, pie, line, ...
            ->backgroundColor($arrColor)
            ->color($lineaColor);
//            ->color('#ff0000');                 // dataset configuration presets.
        return view('reportes.chart', compact('title', 'contactos', 'chart', 'tipo', 'muestra',
                                                'fecha_desde', 'fecha_hasta'));
    }
}
