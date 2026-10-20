<?php

namespace App\Http\Controllers;

use App\Models\Grafiche;
use App\Http\Requests\StoreGraficheRequest;

class GraficheController extends Controller
{
    public function index()
    {
        $progetti = Grafiche::all();

        return view('progetti.index', compact('progetti'));
    }

    public function store(StoreGraficheRequest $request)
    {
        Grafiche::create($request->validated());

        return redirect()->route('progetti.index');
    }
}
