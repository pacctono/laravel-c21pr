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
        $ruta = request()->path();
        $diaSemana = $this->diaSemana;

        if ('' == $orden or $orden == null) {
            $orden = 'id';
        }
    
        $contactos = Contacto::select('user_id', DB::raw('count(*) as atendidos'))
                                    ->groupBy('user_id')
                                    ->get();

        return view('reportes.index', compact('title', 'contactos', 'ruta', 'diaSemana'));
    }
/**
 *  There are a few methods you can use in all datasets (regardless of the type, or charting library).
 *  These includes:
 *
 *  type(string $type) - Set the dataset type.
 *  values($values) - Set the dataset values.
 *  options($options, bool $overwrite = false) - Set the dataset options.
 */
    public function chart($tipo = null)
    {
        if ('' == $tipo or $tipo == null) {
            $tipo = 'line';
        }

        $chart = new SampleChart;

// Add the dataset (we will go with the chart template approach)
        $chart->dataset('Sample', $tipo, [100, 65, 84, 45, 90])     // bar, pie, line, ...
            ->color('#ff0000');                 // dataset configuration presets.
/*            ->options([
                'borderColor' => '#ff0000'
            ]);*/
        return view('reportes.chart', ['chart' => $chart]);
    }
}
