@extends('layouts.site')

@section('content')
@section('title', 'Nuovo libro')

<h1>Nuovo libro</h1>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@else
    <div class="alert alert-danger">    
        {{ session('error') }}    
    </div>  
@endif

<form action="{{ route('libri.create') }}" method="POST">
    @csrf

    <label for="titolo">Titolo:</label>
    <input type="text" name="titolo" id="titolo" required><br>

    <label for="isbn">ISBN:</label>
    <input type="text" name="isbn" id="isbn" required><br>

    <label for="prezzo">Prezzo:</label>
    <input type="number" name="prezzo" id="prezzo" step="0.01" required><br>

    <button type="submit">Salva</button>

</form>

@stop