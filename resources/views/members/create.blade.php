@extends('master')

@section('title')
	Nieuw lid
@endsection


@section('content')
<!-- Dit is het formulier voor het aanmaken van een nieuw lid -->


<h1>Nieuw lid</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'members']) !!}

	@include ('members.form')
	
{!! Form::close() !!}

@endsection