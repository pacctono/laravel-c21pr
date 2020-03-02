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
        $arrTmp = VistaCliente::where('telefono', '!=', '')->orderBy('telefono')
                        ->get(['id', 'name', 'tipo', 'telefono', 'user_id', 'created_at'])
                        ->all();
        $users = User::where('activo', true)->get(['id', 'name']);
        $precios = Price::all();

        $asesores = [];
        foreach ($users as $v) $asesores[$v->id] = $v->name;

        $prices = [];
        foreach ($precios as $v) $prices[] = [
                                    $v->id, $v->descripcion, $v->descripcion_alquiler
                                ];

        $vClientes = [];
        foreach ($arrTmp as $v) {
            if ('' != $v->telefono) $vClientes[$v->telefono] = [
                    'id' => $v->id,
                    'nb' => $v->name,
                    'tp' => $v->tipo,
                    'uid' => $v->user_id,
                    'fc' => $v->created_at->format('d/m/Y'),
                    'ho' => $v->created_at->format('h:i a')
                ];
        }

        return array(json_encode($asesores), json_encode($prices), json_encode($vClientes));
    }
}
