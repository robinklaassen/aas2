@extends('master')

@section('title')
	Nieuw pakket
@endsection

@section('content')
<!-- Dit is het formulier voor het aanmaken van een nieuwe actie -->


<h1>Nieuw pakket</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'event-packages']) !!}

	@include ('event-packages.form')
	
{!! Form::close() !!}

@endsection