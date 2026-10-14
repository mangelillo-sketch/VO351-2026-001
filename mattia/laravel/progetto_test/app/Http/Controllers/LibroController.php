<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;

class LibroController extends Controller
{
    public function index(Request $request){
        $libri = Libro::all();

        //Visualizza la pagina index
        return view(
            'libro.index', 
            [
                "libri" => $libri,
            ]
        );
    }

    public function store(StoreArticoloRequest $request): RedirectResponse
    {
        // Se arriviamo qui, i dati sono già stati validati con successo
        $datiValidati = $request->validated(); // Solo i campi validati

        // Logica di business (slug, flag, ecc.)
        $datiValidati['slug'] = Str::slug($datiValidati['titolo']);
        $datiValidati['pubblicato'] = $request->has('pubblicato');

        Articolo::create($datiValidati); // Mass Assignment sicuro

        return redirect()->route('articoli.index')
                         ->with('messaggio_successo', 'Articolo creato!');
    }
}

class StoreArticoloRequest extends FormRequest
{
 /** Autorizza la richiesta (autenticazione, permessi) */
 public function authorize(): bool
 {
    return true; // Usa policy o gate per logiche complesse
 }

 /** Regole di validazione applicate ai dati in ingresso */
 public function rules(): array
 {
    return [
    'titolo' => 'required|string|min:5|max:255',
    'contenuto' => 'required|string|min:10',
    'pubblicato' => 'nullable|boolean'
    ];
 }

 /** Messaggi di errore personalizzati (opzionale) */
 public function messages(): array
 {
    return [
    'titolo.required' => 'Il campo titolo è obbligatorio.',
    'titolo.min' => 'Il titolo deve contenere almeno 5 caratteri.',
    'contenuto.required' => 'Il contenuto non può essere vuoto.',
    ];
 }
}
