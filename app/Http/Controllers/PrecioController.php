<?php

namespace App\Http\Controllers;

use App\Precio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrecioController extends Controller
{
    protected $tipo = 'Precios';

    public function index($orden = null)
    {
        if (!(Auth::check())) {
            return redirect('login');
        }
        if (!auth()->user()->is_admin) {
            $user = auth()->user();
            return redirect()->route('users.show', ['user' => $user]);
        }

        $title = 'Listado de ' . $this->tipo;

        if ('' == $orden or $orden == null) {
            $orden = 'id';
        }
        $precios = Precio::orderBy($orden)->paginate(10);

        return view('precio.index', compact('title', 'precios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deseo  $deseo
     * @return \Illuminate\Http\Response
     */
    public function show(Deseo $deseo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Deseo  $deseo
     * @return \Illuminate\Http\Response
     */
    public function edit(Deseo $deseo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deseo  $deseo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deseo $deseo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deseo  $deseo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deseo $deseo)
    {
        //
    }
}
