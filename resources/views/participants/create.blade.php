@extends('master')

@section('title')
	Nieuwe deelnemer
@endsection


@section('content')
<!-- Dit is het formulier voor het aanmaken van een nieuwe deelnemer -->


<h1>Nieuwe deelnemer</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'participants']) !!}

	@include ('participants.form')
	
{!! Form::close() !!}

@endsection