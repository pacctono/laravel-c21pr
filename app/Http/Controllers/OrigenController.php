<?php

namespace App\Http\Controllers;

use App\Origen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrigenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $tipo = 'Origenes';

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
        $origenes = Origen::orderBy($orden)->paginate(10);

        return view('origen.index', compact('title', 'origenes'));
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
     * @param  \App\Origen  $origen
     * @return \Illuminate\Http\Response
     */
    public function show(Origen $origen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Origen  $origen
     * @return \Illuminate\Http\Response
     */
    public function edit(Origen $origen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Origen  $origen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Origen $origen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Origen  $origen
     * @return \Illuminate\Http\Response
     */
    public function destroy(Origen $origen)
    {
        //
    }
}
