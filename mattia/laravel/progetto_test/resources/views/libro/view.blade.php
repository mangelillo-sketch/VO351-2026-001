@extends('layouts.site')

@section('content')
@section('title', 'Visualizza libro')

<h1>Visualizza libro</h1>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div>
    <p><strong>Titolo:</strong> {{ $libro->titolo }}</p>
    <p><strong>ISBN:</strong> {{ $libro->isbn }}</p>
    <p><strong>Prezzo:</strong> {{ $libro->prezzo }}</p>
</div>

@stop