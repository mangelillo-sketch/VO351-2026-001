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

    public function edit($id)
 {
    $progetto = Grafiche::find($id);

    if ($progetto === null) {
        return redirect()->route('progetti.index');
    }

    return view('progetti.edit', [
        'progetto' => $progetto
    ]);
 }

 public function update(StoreGraficheRequest $request, $id)
 {
    $progetto = Grafiche::find($id);

    if ($progetto === null) {
        return redirect()->route('progetti.index');
    }

    $validated = $request->validated();

    $progetto->titolo = $validated['titolo'];
    $progetto->descrizione = $validated['descrizione'];
    $progetto->categoria = $validated['categoria'];
    $progetto->anno = $validated['anno'];

    $progetto->save();

    return redirect()->route('progetti.index');
 }

 public function destroy($id)
 {
    $progetto = Grafiche::find($id);

    if ($progetto === null) {
        return redirect()->route('progetti.index');
    }

    $progetto->delete();

    return redirect()->route('progetti.index');
 }
}