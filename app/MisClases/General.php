<?php

namespace App\MisClases;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;
//use MisClases\Fecha;

class General {
    public const LINEASXPAGINA = 15;

    public const menuHorizontal = [
                    'home' => 'Home', 'contactos' => 'Contactos',
                    'users' => 'Asesores', 'turnos' => 'Turnos',
                    'agenda' => 'Agenda', 'propiedades' => 'Propiedades',
                    'clientes' => 'Clientes'
                ];
    public const estadisticaContacto = [
                    'Asesor' => ['Contactos X asesor', true],
                    'Conexion' => ['Conexión X asesor', true],
                    'Fecha' => ['Contactos X fecha', false],
                    'Origen' => ['Contactos X origen', true],
                ];
    public const estadisticaPropiedad = [
                    'Lados' => ['Lados X Asesor', true],
                    'Comision' => ['Comision X Asesor', true],
                    'Negociaciones' => ['Negociaciones X mes', true],
                    'LadMes' => ['Lados X mes', true],
                    'ComMes' => ['Comision X mes', true],
                ];

    public static function enteroEn($numero) {
        if (null == $numero) return '';
        return number_format($numero, 0, ',', '.');
    }

    public static function flotanteEn($numero) {
        if (null == $numero) return '';
        return number_format($numero, 2, ',', '.');
    }

    public static function rifF($nroRif) {
        if (null == $nroRif) return '';
        $numero = substr($nroRif, 1);
        if (9 > strlen($numero)) $numero = str_pad($numero, 9, '0', STR_PAD_LEFT);
        return substr($nroRif, 0, 1) . '-' . substr($numero, 0, 8) . '-' . substr($numero, -1);
    }

    public static function telefonoF($nroTelefono) {
        if (null == $nroTelefono) return '';
        return '0' . substr($nroTelefono, 0, 3) . '-' .
                        substr($nroTelefono, 3, 3) . '-' . substr($nroTelefono, 6);
    }

    public static function fechaEn($fecha, $zona=false) {
        if (null == $fecha) return '';
	    if ($zona) $fecha = $fecha->timezone(Fecha::$ZONA);
        return $fecha->format('d/m/Y');
    }

    public static function fechaBd($fecha) {
        if (null == $fecha) return '';
        return $fecha->format('Y-m-d');
    }

    public static function fechaDiaSemana($fecha, $zona=false) {
        if (null == $fecha) return '';
	    if ($zona) $fecha = $fecha->timezone(Fecha::$ZONA);
        return substr(Fecha::$diaSemana[$fecha->dayOfWeek], 0, 3);
    }

    public static function fechaConHora($fecha, $zona=false) {
        if (null == $fecha) return '';
	    if ($zona) $fecha = $fecha->timezone(Fecha::$ZONA);
        return $fecha->format('d/m/Y h:i a');
    }

    public static function tiempoCreado($fecha) {
        if (null == $fecha) return '';
        return Carbon::parse($fecha)->timezone(Fecha::$ZONA)
                        ->diff(Carbon::now(Fecha::$ZONA))
                        ->format('%y años, %m meses y %d dias');
    }

    public static function columnas($tabla)
    {
        $valores = DB::select("SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE,
                                        DATA_TYPE, COLUMN_TYPE, COLUMN_COMMENT
                               FROM   INFORMATION_SCHEMA.COLUMNS
                               WHERE  TABLE_NAME = '$tabla'");
        $cols = Array();
        foreach($valores as $fila) {
            if ('enum' == $fila->DATA_TYPE) {
                $tipos = explode(',',           // Crea arreglo de los valores 'enum' separados por ,
                    str_replace('"', '',                        // Elimina "s
                        str_replace("'", "",                    // Elimina 's
                            substr($fila->COLUMN_TYPE, 5, -1)   // Elimina enum( y )
                        )
                    )
                );
                if ((1 < strlen($fila->COLUMN_COMMENT)) and
                    strpos($fila->COLUMN_COMMENT, ',', 1)) {
                    $come = explode(',', $fila->COLUMN_COMMENT);
                } else $come = $tipos;        // Esto solo debe ocurrir si la col es enum.
                $tipo = Array();
                for ($j=0; $j<count($tipos); $j++) {
                    if ($j < count($come)) $tipo[$tipos[$j]] = $come[$j];
                    else $tipo[$tipos[$j]] = 'S/DESC';
                }
            } else {
                $tipo = $fila->COLUMN_TYPE;
                $come = $fila->COLUMN_COMMENT;
            }
            $cols[$fila->COLUMN_NAME] = array(
                'tipo' => $fila->DATA_TYPE,
                'xdef' => $fila->COLUMN_DEFAULT,
                'opcion' => $tipo,
                'come' => $come,
            );
        }
        return $cols;
    }       // Final del metodo columnas.

    public static function grabarArchivo()
    {
        function nulo($valor, $def='') {
            if (is_null($valor)) {
                $valor = $def;
            }
            return $valor;
        }
        $users   = \App\User::get();                     // Todos los usuarios (asesores).
        $users[0]['name'] = 'Asesor otra oficina';
        $propiedades = \App\Propiedad::where('id', '>', 0);   // condición dummy, solo para continuar armando la consulta.

        $totales = '';
/*
 * Calculo de totales por 'asesor' (user).
 */
        foreach ($users as $user) {
            $props = clone $propiedades;               // Los query modifican el arreglo propiedades.
            $id = $user->id;
            $props = $props->where(function ($query) use ($id) {
                                $query->where('asesor_captador_id', $id)
                                        ->orWhere('asesor_cerrador_id', $id);
                            });
            $arreglo = \App\Propiedad::totales($props, True, $user->id, $user->id);
            array_unshift($arreglo, 'A', $user->id);
            $totales .= json_encode($arreglo) . "\n";
            //if (6 == $id) dd($totales);        # Hasta aqui, pareciera todo esta bien.
        }
/*
 * Calculo de totales por mes.
 */
        $fecha = DB::select("SELECT DATE_FORMAT(fecha_firma, '%Y') AS Agno,
                                    DATE_FORMAT(fecha_firma, '%m') AS Mes
                             FROM   propiedads
                            GROUP BY 1, 2");
        $anoMes = Array();
        foreach($fecha as $fila) {
            if (array_key_exists($fila->Agno, $anoMes))
                $anoMes[$fila->Agno][] = $fila->Mes;
            else $anoMes[$fila->Agno][] = $fila->Mes;
        }
        foreach($anoMes as $agno=>$meses) {
            foreach ($meses as $mes) {
                $props = clone $propiedades;               // Los query modifican el arreglo propiedades.
                if (is_null($agno) or is_null($mes)) {
                    $props = $props->whereNull('fecha_firma');
                } else {
                    $props = $props->whereYear('fecha_firma', $agno)
                                    ->whereMonth('fecha_firma', $mes);
                }
                $arreglo = \App\Propiedad::totales($props);
                if (is_null($agno) or is_null($mes)) {
                    array_unshift($arreglo, 'M', Fecha::hoy()->format('Y') . '-' . '00');
                } else {
                    array_unshift($arreglo, 'M', $agno . '-' . $mes);
                }
                $totales .= json_encode($arreglo) . "\n";
            }
        }
        //dd($totales);
/*
 * Calculo de totales por 'estatus'. cols es usado, al final, para grabar las tablas.
 */
        $colsPropiedad = self::columnas('propiedads');
        $estatus = $colsPropiedad['estatus']['opcion'];
        foreach ($estatus as $op=>$desc) {
            $props = clone $propiedades;               // Los query modifican el arreglo propiedades.
            $props = $props->where('estatus', $op);
            $arreglo = \App\Propiedad::totales($props, False);
            array_unshift($arreglo, 'E', $op);
            $totales .= json_encode($arreglo) . "\n";
        }
        //dd($totales);
/*
 * Calculo de totales por 'asesor' (user) y mes.
 */
        foreach ($users as $user) {
            foreach($anoMes as $agno=>$meses) {
                foreach ($meses as $mes) {
                    $props = clone $propiedades;               // Los query modifican el arreglo propiedades.
                    if (is_null($agno) or is_null($mes)) {
                        $props = $props->where(function ($Q) use ($user) {
                                            $Q->where('asesor_captador_id', $user->id)
                                                ->orWhere('asesor_cerrador_id', $user->id);
                                        })
                                        ->whereNull('fecha_firma');
                    } else {
                        $props = $props->where(function ($Q) use ($user) {
                                            $Q->where('asesor_captador_id', $user->id)
                                                ->orWhere('asesor_cerrador_id', $user->id);
                                        })
                                        ->whereYear('fecha_firma', $agno)
                                        ->whereMonth('fecha_firma', $mes);
                    }
                    $arreglo = \App\Propiedad::totales($props, True, $user->id, $user->id);
                    if (is_null($agno) or is_null($mes)) {
                        array_unshift($arreglo, 'AM', $user->id,
                                            Fecha::hoy()->format('Y') . '-' . '00');
                    } else {
                        array_unshift($arreglo, 'AM', $user->id, $agno . '-' . $mes);
                    }
                    //dd($arreglo);
                    $totales .= json_encode($arreglo) . "\n";
                }
            }
        }
        //dd($totales);
/*
 * Calculo de totales por mes y asesor (user).
 */
        foreach($anoMes as $agno=>$meses) {
            foreach ($meses as $mes) {
                foreach ($users as $user) {
                    $props = clone $propiedades;               // Los query modifican el arreglo propiedades.
                    if (is_null($agno) or is_null($mes)) {
                        $props = $props->where(function ($Q) use ($user) {
                                            $Q->where('asesor_captador_id', $user->id)
                                                ->orWhere('asesor_cerrador_id', $user->id);
                                        })
                                        ->whereNull('fecha_firma');
                    } else {
                        $props = $props->where(function ($Q) use ($user) {
                                            $Q->where('asesor_captador_id', $user->id)
                                                ->orWhere('asesor_cerrador_id', $user->id);
                                        })
                                        ->whereYear('fecha_firma', $agno)
                                        ->whereMonth('fecha_firma', $mes);
                    }
                    $arreglo = \App\Propiedad::totales($props, True, $user->id, $user->id);
                    if (is_null($agno) or is_null($mes)) {
                        array_unshift($arreglo, 'MA',
                                            Fecha::hoy()->format('Y') . '-' . '00', $user->id);
                    } else {
                        array_unshift($arreglo, 'MA', $agno . '-' . $mes, $user->id);
                    }
                    $totales .= json_encode($arreglo) . "\n";
                }
            }
        }
        //dd($totales);
/*
 * Calculo de totales generales.
 */
        $arreglo = \App\Propiedad::totales($propiedades);
        array_unshift($arreglo, 'T', 'T');
        $totales .= json_encode($arreglo) . "\n";
        $propiedades = $propiedades->get();
        $props       = '';
        foreach ($propiedades as $p) {
            $props .= json_encode(array ($p->id, $p->codigo, $p->reserva_en,
                        $p->firma_en, $p->negociacion, $p->nombre,
                        $p->tipo_id, $p->metraje, $p->habitaciones, $p->banos,
                        $p->niveles, $p->puestos, $p->anoc, $p->caracteristica_id,
                        $p->descripcion, $p->direccion, $p->ciudad_id, $p->codigo_postal,
                        $p->municipio_id, $p->estado_id, $p->cliente_id,
                        $p->estatus, $p->moneda, $p->precio, $p->comision,
                        $p->reserva_sin_iva, $p->iva, $p->reserva_con_iva,
                        $p->compartido_con_iva, $p->compartido_sin_iva,
                        $p->lados, $p->franquicia_reservado_sin_iva,
                        $p->franquicia_reservado_con_iva, $p->porc_franquicia,
                        $p->franquicia_pagar_reportada, $p->reportado_casa_nacional,
                        $p->porc_regalia, $p->porc_compartido, $p->regalia, $p->sanaf5_por_ciento,
                        $p->oficina_bruto_real, $p->base_honorarios_socios,
                        $p->base_para_honorarios, $p->asesor_captador_id,
                        $p->asesor_captador, $p->porc_captador_prbr, $p->captador_prbr,
                        $p->puntos_captador,
                        $p->porc_gerente, $p->gerente, $p->asesor_cerrador_id,
                        $p->asesor_cerrador, $p->porc_cerrador_prbr, $p->cerrador_prbr,
                        $p->puntos_cerrador,
                        $p->porc_bonificacion, $p->bonificaciones,
                        nulo($p->comision_bancaria, 0), $p->ingreso_neto_oficina,
                        $p->precio_venta_real, $p->puntos, nulo($p->numero_recibo),
                        nulo($p->pago_gerente), nulo($p->factura_gerente),
                        nulo($p->pago_asesores), nulo($p->factura_asesores),
                        nulo($p->pago_otra_oficina), nulo($p->pagado_casa_nacional),
                        nulo($p->estatus_sistema_c21),
                        (($p->reporte_casa_nacional)?
                                number_format($p->reporte_casa_nacional, 0, ',', '.'):''),
                        nulo($p->factura_AyS), nulo($p->comentarios))) . "\n";
        }
        //dd($totales);
        //dd($users);
        $asesores = '';
        foreach ($users as $s) $asesores .= json_encode($s) . "\n";
/*
 * Estos archivos grabados con Storage seran guardados en 'storage/app/public'
 * Usando: composer artisan storage:link, se crea un enlace que permite acceder
 * los archivos desde public/storage
 */
        $control = self::fechaDiaSemana(Carbon::now(Fecha::$ZONA)) . ', ' .
                    Carbon::now(Fecha::$ZONA)->format('d-m-Y h:i a') . "\n";
        Storage::put('public/celular/asesores.txt', $asesores);
        $control .= "asesores.txt\n";
        Storage::put('public/celular/propiedades.txt', $props);
        $control .= "propiedades.txt\n";
        $props = '';
//        $propiedades = \App\Propiedad::where('id', '>', 0)->get();
        foreach ($propiedades as $p) {
            $props .= str_replace(',"comBanc":"",', ',"comBanc":0,',
                                str_replace('null', '""', json_encode($p))) . "\n";
        }
        Storage::put('public/celular/propiedads.txt', $props);
        $control .= "propiedads.txt\n";
        $clients = '';
        $clientes = \App\Cliente::get();
        foreach ($clientes as $c) $clients .= json_encode($c) . "\n";
        Storage::put('public/celular/clientes.txt', $clients);
        $control .= "clientes.txt\n";
        $contacts = '';
        $contactos = \App\Contacto::get();
        foreach ($contactos as $c) $contacts .= json_encode($c) . "\n";
        Storage::put('public/celular/contactos.txt', $contacts);
        $control .= "contactos.txt\n";
        $agends = '';
        $agendas = \App\Agenda::get();
        foreach ($agendas as $a) $agends .= json_encode($a) . "\n";
        Storage::put('public/celular/agendas.txt', $agends);
        $control .= "agendas.txt\n";
        $turns = '';
        $turnos = \App\Turno::get();
        foreach ($turnos as $t) $turns .= json_encode($t) . "\n";
        Storage::put('public/celular/turnos.txt', $turns);
        $control .= "turnos.txt\n";
        Storage::put('public/celular/totales.txt', $totales);
        $control .= "totales.txt\n";
        foreach($colsPropiedad as $nombCol => $arr) {
            if (strpos($nombCol, '_id')) {
                $nombMod = substr($nombCol, 0, strpos($nombCol, '_id'));
                if (('user' != $nombMod) and ('cliente' != $nombMod) and
                    (0 !== strpos($nombMod, 'asesor'))) {
                    $modelo  = '\App\\' . ucfirst($nombMod);  // Siempre anteponer App\ aunque el namespace App.
                    $arreglo = $modelo::get(['id', 'descripcion']);
                    $objeto  = Array();
                    foreach($arreglo as $obj) $objeto[$obj['id']] = $obj['descripcion'];
                    Storage::put('public/celular/' . $nombMod . 's.txt', json_encode($objeto));
                    $control .= $nombMod . "s.txt\n";
                }
            }
            if ('enum' == $arr['tipo']) {
                Storage::put('public/celular/' . $nombCol . '.txt', json_encode($arr['opcion']));
                $control .= $nombCol . ".txt\n";
            }
        }	// foreach($colsPropiedad as $nombCol => $arr) {
        $colsContacto = self::columnas('contactos');
        foreach($colsContacto as $nombCol => $arr) {
            if (strpos($nombCol, '_id')) {
                $nombMod = substr($nombCol, 0, strpos($nombCol, '_id'));
                if (('user' != $nombMod) and ('cliente' != $nombMod) and ('tipo' != $nombMod) and	// tipo ya fue creado.
                    ('propiedad' != $nombMod) and                     // Carajo! Esto no lo entiendo, pero, existe.
                    (0 !== strpos($nombMod, 'asesor'))) {
                    $modelo  = '\App\\' . ucfirst($nombMod);  // Siempre anteponer App\ aunque el namespace App.
                    $arreglo = $modelo::get(['id', 'descripcion']);
                    $objeto  = Array();
                    foreach($arreglo as $obj) $objeto[$obj['id']] = $obj['descripcion'];
                    Storage::put('public/celular/' . $nombMod . 's.txt', json_encode($objeto));
                    $control .= $nombMod . "s.txt\n";
                }
            }
            if ('enum' == $arr['tipo']) {
                Storage::put('public/celular/' . $nombCol . '.txt', json_encode($arr['opcion']));
                $control .= $nombCol . ".txt\n";
            }
        }	// foreach($colsContacto as $nombCol => $arr) {
        Storage::put('public/celular/control.txt', $control);
    }       // Final del metodo grabarArchivo.

    public static function generarPdf($html, $nombre, $accion="ver")
    {
        // Ver: https://mpdf.github.io/ y https://desarrollowebtutorial.com/generar-pdf-en-laravel/
        $namefile = $nombre . '_' . time() . '.pdf';
 
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
 
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path() . '/fonts',
            ]),
            'fontdata' => $fontData + [
                'arial' => [
                    'R' => 'arial.ttf',
                    'B' => 'arialbd.ttf',
                ],
            ],
            'default_font' => 'arial',
            'margin_header' => 10,
            'margin_top' => 25,
            //"format" => [216.0,279.0],  // Carta en dimensiones milimetricas.
            "format" => "letter",  // Carta. Otras opciones: A4, A3, A2, etc.
        ]);
        // $mpdf->SetTopMargin(5);
        //$mpdf->SetHTMLHeader('<h6 align="center">Puente Real</h6>');
        $mpdf->SetHTMLHeader("<div style='text-align:center;'>" .
                            "<img src='" . public_path() . "/img/c21pr.jpg' " . 
                            "title='C21 Puente Real' alt='C21 Puente Real' " .
                            "style='display:block;margin:0 auto;width:50;height:48;'></div>");
        $mpdf->SetHTMLFooter('<div style="font-size:60%;display:inline-block"><span>' .
                                'Piso 1, Centro Comercial Costanera Plaza I, Barcelona, ' .
                                '0281-416.0885.&copy; Copyright 2019-{DATE Y}</span>' .
                                ' ..... <span>' .
                                'Fecha:{DATE j/m/Y} P&aacute;gina:{PAGENO}/{nb}</span></div>');
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        // dd($mpdf);
        if($accion=='ver'){
            $mpdf->Output($namefile,"I");
        }elseif($accion=='descargar'){
            $mpdf->Output($namefile,"D");   // "D": Descargar el archivo. "F": Guardar el archivo.
        }
    }

    public static function idAlAzar($indexIds, &$arregloIds, $min, $max, $arregloUsers, $idNoPermitido=0) {
        $nroIndex = random_int($min, $max);
        $nroId = $arregloUsers[$nroIndex];
        $iPare = 0;
        while (in_array($nroId, $arregloIds) or
                (($max > count($arregloIds)) and ($nroId == $idNoPermitido))) {  // Ultimo elemento del arreglo.
            $nroIndex = random_int($min, $max);
            $nroId = $arregloUsers[$nroIndex];
            $iPare++;
            if (100 < $iPare) {     // Evita ciclo infinito.
                $nroId = 1;
                break;
            }
        }
        if (!in_array($nroId, $arregloIds)) $arregloIds[] = $nroId;
        if ($nroId == $idNoPermitido) {  // Solo sucede cuando el id del sabado es el último de '$ids'.
            $indexIds = 0;
            $nroId = $arregloIds[$indexIds];
        }
        return array($nroId, $indexIds);
    }

    public static function idDelArreglo($indexIds, $arregloIds, $index, $idNoPermitido=0) {
        $indexIds++;
        if (!isset($arregloIds[$indexIds])) $indexIds = 0;
        $nroId = $arregloIds[$indexIds];
        $bPare = false;
        while ($nroId == $idNoPermitido) {
            $indexIds++;
            if (!isset($arregloIds[$indexIds])) $indexIds = 0;
            $nroId = $arregloIds[$indexIds];
            if (0 == $indexIds) {
                if ($bPare) {   // Evita ciclo infinito.
                    $nroId = 1;
                    break;
                }
                $bPare = true;
            }
        }
        return array($nroId, $indexIds);
    }
}
