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

class ReporteController extends Controller
{
    protected $diaSemana = [
        'Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'
    ];
    protected $tipo = 'Reporte';

    public function index($orden = null)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }

        if (!(Auth::user()->is_admin)) {
            return redirect()->back();
        }

        $title = $this->tipo . ' por Asesor';

        $contactos = Contacto::select('user_id', DB::raw('count(*) as atendidos'))
                                    ->groupBy('user_id')
                                    ->get();

        return view('reportes.index', compact('title', 'contactos'));
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

        $title = $this->tipo . ' por Asesor';
        $legenda = $title;

        if ('' == $tipo or $tipo == null) {
            $tipo = 'line';
        }

        $chart = new SampleChart;

        $contactos = Contacto::select('user_id', DB::raw('count(*) as atendidos'))
                                    ->groupBy('user_id')
                                    ->get();

// Add the dataset (we will go with the chart template approach)
        $arrEtiq  = array();
        $arrData  = array();
        $arrColor = array();

        $intervalo = 0xffffff/count($contactos);
        $hexColor  = 0x0;
        foreach ($contactos as $contacto) {
            $arrEtiq[]  = substr($contacto->user->name, 0, 20);
            $arrData[]  = $contacto->atendidos;
            $strColor   = str_pad(dechex($hexColor), 6, '0', STR_PAD_LEFT);
            $arrColor[] = '#' . $strColor;
            $hexColor  += $intervalo;
        }
//        dd($arrColor);
        $chart->displayLegend(true);
        $chart->labels($arrEtiq);
        $chart->displayAxes(true);
        $chart->dataset($legenda, $tipo, $arrData)     // bar, pie, line, ...
            ->backgroundColor($arrColor)
            ->color('#00ff00');
//            ->color('#ff0000');                 // dataset configuration presets.
        return view('reportes.chart', compact('title', 'contactos', 'chart'));
    }
}
