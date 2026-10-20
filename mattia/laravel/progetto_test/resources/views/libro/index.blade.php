@extends('layouts.site')

@section('content')
@section('title', 'Tutti i libri')

<h1>PAGINA LIBRI</h1>

<table>
    <tr>
        <th>identificativo </th>
        <th>Titolo</th>
        <th>isbn</th>
        <th>prezzo</th>
    </tr>
    @foreach ($libri as $libro)
        <tr>
            <td>{{ $libro->identificativo  }}</td>
            <td>{{ $libro->titolo }}</td>
            <td>{{ $libro->isbn }}</td>
            <td>{{ $libro->prezzo }}</td>
        </tr>
    @endforeach
</table>        

@stop