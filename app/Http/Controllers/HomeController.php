<?php

namespace App\Http\Controllers;

use App\User;
use App\Propiedad;
use App\Texto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;     // PC
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Carbon\Carbon;
use App\MisClases\Fecha;

class HomeController extends Controller
{
    protected static function comparar($a, $b) {
        if ($a['id'] == $b['id']) {
            if ($a['sec'] == $b['sec']) return 0;
            return ($a['sec'] < $b['sec']) ? -1 : 1;
        }
        return ($a['id'] > $b['id']) ? -1 : 1;
    }
    protected function misPropiedades($files)
    {
        $propiedad = [];
// los nombre de archivo tienen que tener la estructura: {propiedad_id}_{codigo}[-{secuencia}].ext        
        foreach($files as $filename) {
            if (1 != substr_count($filename, '.')) continue;
            list($nombre, $extension) = explode('.', $filename);
            if (1 != substr_count($nombre, '_')) continue;
            list($propiedad_id, $codigo_sec) = explode('_', $nombre);
            if (0 == substr_count($codigo_sec, '-')) continue;    // Solo interesa la primera imagen.
            list($codigo, $sec) = explode('-', $codigo_sec);
            if (!is_numeric($propiedad_id) or !is_numeric($codigo) or !is_numeric($sec))
                continue;
            $prop = Propiedad::findOrFail($propiedad_id);
            if (!Auth::user()->is_admin and (Auth::user()->id != $prop->asesor_captador_id) and
                (Auth::user()->id != $prop->asesor_cerrador_id)) continue;
            if ('A' != $prop->estatus) continue;        // La propiedad no esta activa.
            $todosNombreProp[] = [
                'nombreImagen' => $filename,
                'id' => $propiedad_id,
                'codigo' => $codigo,
                'sec' => $sec,
                'asesor_id' => $prop->asesor_captador_id,
                'nombre' => $prop->nombre,
            ];
            //if (50 <= count($todosNombreProp)) break;
        }
        usort($todosNombreProp, "self::comparar");  // Ordenar por id, asc y sec, desc.
        foreach ($todosNombreProp as $nombre) {     // Suprime nombres duplicados, con diferentes sec, se escoge el de menor sec.
            $id = $nombre['id'];
            if (!isset($idant)) $nombreProp[] = $nombre;
            elseif ($id != $idant) $nombreProp[] = $nombre;
            $idant = $id;
        }
        return collect($nombreProp);
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($alert=0)
    {
        $fecha_desde = Fecha::hoy();
        $fecha_hasta = Fecha::hoy()->addDays(3)->endOfDay();
        $cumpleaneros = User::cumpleanos($fecha_desde, $fecha_hasta)->get();
        $textos = Texto::all();
        $hoy = Fecha::hoy()->format('d-m');
        $manana = Fecha::manana()->format('d-m');
        $texto1 = $textos->find(1);
        $texto2 = $textos->find(2);
        $texto3 = $textos->find(3);
        $texto4 = $textos->find(4);
        $texto5 = $textos->find(5);
        //dd($cumpleaneros->first()->fecha_cumpleanos);
        $nombre = Auth::user()->name;
        if (0 == $alert) {
            $alertar = '';
        } elseif (1 == $alert) {
            $alertar = 'Bienvenido a su turno, ' . $nombre . ', ha sido puntual!';
        } else {
            if (-1 == $alert) {
                $alertar = $nombre . '! Usted no tiene permitido acceder a esa página.';
            } else {
                $alertar = '! Usted llego tarde. Su turno comienza a las ';
                if (-2 == $alert) $alertar = $nombre . $alertar . '8:30am';
                elseif (-3 == $alert) $alertar = $nombre . $alertar . '12:30am';
                else $alertar = 'Disculpe! Esta notificación no debería existir.';
            }
        }
        $foto = substr(Auth::user()->email, 0, strpos(Auth::user()->email, '@')) . '.jpg';
        $foto = 'fotos/' . $foto;
/* Usando 'system'.
        $nLineas = 0;
        $fortuna = '';
        $ciclo = 0;
        while (1 != $nLineas) {
            $ciclo++;
            system("fortune >storage/fortuna.txt", $resultado);
            if (0 == $resultado) {
                $fortunas = file("storage/fortuna.txt");
                $nLineas = count($fortunas);
                if (1 == $nLineas) $fortuna = trim($fortunas[0]);
            } else break;
            if (100 < $ciclo) break;
        }
        $fortuna = exec("/usr/games/fortune");
        dd($resultado, $nLineas, $ciclo, $fortuna);*/
/* Usando "Symfony\Component\Process\Process"
        $proceso = new Process('fortune');
        $proceso->run();
        if ($proceso->isSuccessful()) $fortuna = $proceso->getOutput();
        else $fortuna = 'Fallo el proceso fortuna';
        dd($fortuna);*/
        $rutaPrevia = redirect()->getUrlGenerator()->previous();
        $login = stripos($rutaPrevia, 'login');
        $cumpleanos = '';
        if ((Auth::user()->is_admin) && (0 < count($cumpleaneros)) && $login) {
            $cumpleanos = '<div class="m-0 col-lg-8">';
            foreach ($cumpleaneros as $cumpleano) {
                $cumpleanos .= "\n" . '<div class="row">
                    <div class="col-lg-4">';
                $cumpleanos .= "{$cumpleano->name}\n";
                $cumpleanos .= '</div>
                    <div class="col-lg-4">';
                $cumpleanos .= $cumpleano->fecha_cumpleanos->format('d-m');
                if ($hoy == $cumpleano->fecha_cumpleanos->format('d-m'))
                    $cumpleanos .= '<a href="/cumpleano/' . $cumpleano->id . '" class="btn btn-link"
                            title="Enviar correo a ' . "'{$cumpleano->name}'" . ', porque esta de cumpleaños!">
                            <span class="oi oi-envelope-closed"></span>
                        </a>';
                $cumpleanos .= "\n</div>\n</div>\n";
            }
            $cumpleanos .= "</div>\n";
        }
        //dd($cumpleanos);
        $hoyCumpleanos = false;
        if ((!is_null(Auth::user()->fecha_nacimiento)) and
            ($hoy == Auth::user()->fecha_nacimiento->format('d-m')))
            $hoyCumpleanos = true && $login;
        //dd($hoy, Auth::user()->fecha_cumpleanos->format('d-m'));
        $dir = public_path('imgprop');
        $files = array_diff(scandir($dir, SCANDIR_SORT_DESCENDING), array('..', '.'));  // Cada nombre de imagen comienza con el id de la propiedad. Primero los mayores, los más recientes creados.
        $misPropiedades = $this->misPropiedades($files);    // Nombre imagen; id, codigo y nombre de propiedad.
        //dd($files, $misPropiedades, Propiedad::where('asesor_captador_id', 16)->get());
        $users   = User::get(['id', 'name']);     // Todos los usuarios (asesores), incluso los no activos.
        $users[0]['name'] = 'Administrador';
        $asesor = array();
        foreach($users as $user) $asesor[$user->id] = $user->name;
        return view('home', compact('cumpleanos', 'foto', 'hoyCumpleanos',
//                    'texto1', 'texto2', 'texto3', 'texto4', 'texto5',
                    'asesor', 'misPropiedades', 'alertar'));
    }
}
