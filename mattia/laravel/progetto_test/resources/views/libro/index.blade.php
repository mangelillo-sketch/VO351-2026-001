@extends('layouts.site')

@section('content')
@section('title', 'Tutti i libri')

<h1>PAGINA LIBRI</h1>

<pre>
    <?php print_r($libri); ?>
</pre>

@stop