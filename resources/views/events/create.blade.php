@extends('master')

@section('title')
	Nieuw evenement
@endsection

@section('content')
<!-- Dit is het formulier voor het aanmaken van een nieuw evenement -->


<h1>Nieuw evenement</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'events']) !!}

	@include ('events.form')
	
{!! Form::close() !!}

@endsection