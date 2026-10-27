@extends('layouts.app')

@section('content')
    <h1>Modifica progetto</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $errore)
                <li>{{ $errore }}</li>
            @endforeach
        </ul>
    @endif

    <form
        action="{{ route('progetti.update', $progetto->id) }}"
        method="POST"
    >
        @csrf

        <div>
            <label for="titolo">Titolo</label>
            <input
                type="text"
                id="titolo"
                name="titolo"
                value="{{ old('titolo', $progetto->titolo) }}"
                maxlength="255"
                required
            >
        </div>

        <div>
            <label for="descrizione">Descrizione</label>
            <textarea
                id="descrizione"
                name="descrizione"
                required
            >{{ old('descrizione', $progetto->descrizione) }}</textarea>
        </div>

        <div>
            <label for="categoria">Categoria</label>
            <input
                type="text"
                id="categoria"
                name="categoria"
                value="{{ old('categoria', $progetto->categoria) }}"
                maxlength="255"
                required
            >
        </div>

        <div>
            <label for="anno">Anno</label>
            <input
                type="number"
                id="anno"
                name="anno"
                value="{{ old('anno', $progetto->anno) }}"
                min="1000"
                max="9999"
                required
            >
        </div>

        <button type="submit">Salva modifiche</button>
    </form>

    <a href="{{ route('progetti.index') }}">Annulla</a>
@endsection