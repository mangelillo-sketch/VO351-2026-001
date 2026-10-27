@extends('layouts.app')

@section('content')

<!-- <link rel="stylesheet" href="{{ URL::asset('css/backend/grid.css'); }} " /> -->
<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #f5f5f5;
        color: #222;
        font-family: Arial, sans-serif;
    }

    .contenitore {
        max-width: 1100px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .colonne {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        align-items: start;
    }

    .pannello {
        background: white;
        padding: 24px;
        border-radius: 12px;
    }

    label {
        display: block;
        margin-bottom: 6px;
    }

    input, textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 18px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font: inherit;
    }

    button {
        padding: 12px 20px;
        background: #245c46;
        color: white;
        border: 0;
        border-radius: 6px;
        cursor: pointer;
    }

    .progetto {
        padding: 16px 0;
        border-bottom: 1px solid #ddd;
    }

    .progetto p {
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    .errori {
        color: #b42318;
    }

    @media (max-width: 700px) {
        .colonne {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="contenitore">
    <h1>Portfolio grafiche</h1>

    <div class="colonne">
        {{-- Colonna sinistra: form --}}
        <section class="pannello">
            <h2>Inserisci un progetto</h2>
            @if ($errors->any())
                <ul class="errori" role="alert">
                    @foreach ($errors->all() as $errore)
                        <li>{{ $errore }}</li>
                    @endforeach
                </ul>
            @endif
            <form action="{{ route('progetti.store') }}" method="POST">
                @csrf

                <label for="titolo">Titolo</label>
                <input
                    type="text"
                    id="titolo"
                    name="titolo"
                    value="{{ old('titolo') }}"
                    maxlength="255"
                    required
                >

                <label for="descrizione">Descrizione</label>
            
                <textarea
                    id="descrizione"
                    name="descrizione"
                    rows="4"
                    required
                >{{ old('descrizione') }}</textarea>

                <label for="categoria">Categoria</label>
                <input
                    type="text"
                    id="categoria"
                    name="categoria"
                    value="{{ old('categoria') }}"
                    maxlength="255"
                    required
                >

                <label for="anno">Anno</label>
                <input
                    type="number"
                    id="anno"
                    name="anno"
                    value="{{ old('anno') }}"
                    min="1000"
                    max="9999"
                    required
                >

                <button type="submit">Salva progetto</button>
            </form>
        </section>
            {{-- Colonna destra: elenco --}}
            <section class="pannello">
                <h2>I tuoi progetti</h2>

                @forelse ($progetti as $progetto)
                    <a href="{{ route('progetti.edit', $progetto->id) }}">
                    Modifica
                    </a>

                    <form
                    action="{{ route('progetti.destroy', $progetto->id) }}"
                    method="POST"
                    onsubmit="return confirm('Vuoi eliminare questo progetto?')"
                    >
                    @csrf
                    

                    <button type="submit">Elimina</button>
                </form>
                    <article class="progetto">
                        <h3>{{ $progetto->titolo }}</h3>
                        <p>{{ $progetto->descrizione }}</p>
                        <small>
                            {{ $progetto->categoria }} · {{ $progetto->anno }}
                        </small>
                    </article>
                @empty
                    <p>Non hai ancora inserito progetti.</p>
                @endforelse
        </section>
        </div>
    </div>
@endsection