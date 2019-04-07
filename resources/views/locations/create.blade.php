@extends('master')

@section('title')
	Nieuwe locatie
@endsection


@section('content')
<!-- Dit is het formulier voor het aanmaken van een nieuwe locatie -->


<h1>Nieuwe locatie</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'locations']) !!}

	@include ('locations.form')
	
{!! Form::close() !!}

@endsection