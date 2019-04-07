@extends('master')

@section('title')
	Nieuwe actie
@endsection

@section('content')
<!-- Dit is het formulier voor het aanmaken van een nieuwe actie -->


<h1>Nieuwe actie</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'actions']) !!}

	@include ('actions.form')
	
{!! Form::close() !!}

@endsection