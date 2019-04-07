@extends('master')

@section('title')
	Nieuw vak
@endsection

@section('content')
<!-- Dit is het formulier voor het aanmaken van een nieuw vak -->


<h1>Nieuw vak</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'courses']) !!}

	@include ('courses.form')
	
{!! Form::close() !!}

@endsection