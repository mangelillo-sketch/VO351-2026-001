<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use Illuminate\Foundation\Http\FormRequest;

class LibroController extends Controller
{
    public function index(Request $request){
        //SELECT * FROM libri;
        $libri = Libro::all();

        return view('libro.index', [
            'libri' => $libri
        ]);
    }

    public function create(Request $request){
        
        try{
            if($request->isMethod('post')){
                $validated = $request->validate([
                    'titolo'    => 'required|string|max:255',
                    'isbn'      => 'required|string|max:16',
                    'prezzo'    => 'required|numeric|min:0',
                ]);

                $libro = new Libro();
                $libro->titolo = $validated['titolo'];
                $libro->isbn = $validated['isbn'];
                $libro->prezzo = $validated['prezzo'];
                $libro->save();

                return redirect()->route(
                        'libri.view',
                        [
                            'id' => $libro->identificativo,
                        ]
                    )
                    ->with(['success' => 'Libro inserito con successo.']);
            }
        }catch(QueryException $e){
            return redirect()->back()->with(['error' => 'Errore nell\'inserimento del libro.']);
        }

        return view('libro.create');
    }

    public function view(Request $request){
        $libro = Libro::find($request->query('id'));

        return view('libro.view', [
            'libro' => $libro
        ]);
    }
}
