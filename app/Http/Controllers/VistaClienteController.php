<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Contacto;
use App\VistaCliente;
use App\User;
use App\Price;
use Illuminate\Http\Request;

class VistaClienteController extends Controller
{
    public function vClientes()
    {
        $arrTmp = VistaCliente::where('telefono', '!=', '')->orderBy('id')->orderBy('tipo')
                        ->get(['id', 'cedula', 'name', 'tipo', 'telefono', 'email', 'user_id', 'created_at'])
                        ->all();
        $users = User::get(['id', 'name']);
        $precios = Price::all();

        $asesores = [];
        foreach ($users as $v) {
            if (1 == $v->id) $asesores[1] = 'Administrador';
            else $asesores[$v->id] = $v->name;
        }

        $prices = [];
        foreach ($precios as $v) $prices[] = [
                                    $v->id, $v->descripcion, $v->descripcion_alquiler
                                ];

        $vClientes = [];
        $vTelefonos = [];
        $vCedulas  = [];
        $vCorreos  = [];
        foreach ($arrTmp as $v) {
            if (('' != $v->id) and ('' != $v->tipo)) $vClientes[$v->id . $v->tipo] = [
                    'nb' => $v->name,
                    'uid' => $v->user_id,
                    'fc' => $v->created_at->format('d/m/Y'),
                    'ho' => $v->created_at->format('h:i a')
                ];
            if ('' != $v->cedula) $vCedulas[$v->cedula] = [
                    'id' => $v->id,
                    'tp' => $v->tipo,
                ];
            if ('' != $v->telefono) $vTelefonos[$v->telefono] = [
                    'id' => $v->id,
                    'tp' => $v->tipo,
                ];
            if ('' != $v->email) $vCorreos[$v->email] = [
                    'id' => $v->id,
                    'tp' => $v->tipo,
                ];
        }

        return array(json_encode($asesores), json_encode($prices), json_encode($vClientes),
                    json_encode($vCedulas), json_encode($vTelefonos), json_encode($vCorreos));
    }
}
