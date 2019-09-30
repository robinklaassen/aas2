@extends('master')

@section('title')
Oeps! Foutje.
@endsection

@section('content')

<div class="jumbotron">
    <h1>
        @section('error')
        Oeps, er is iets fout gegaan
        @show
    </h1>

    @section('message')
    <p>
        Het systeem gaf ons de volgende foutmelding: <code>{{ $exception->getMessage() }}</code>
    </p>
    @show

    <p><a class="btn btn-primary btn-lg" href="{{ url()->previous() }}" role="button">Terug</a></p>
    @section('stacktrace')
    <pre>{!! $exception->getTraceAsString() !!}</pre>
    @show
</div>

@endsection