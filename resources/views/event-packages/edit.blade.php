@extends('master')

@section('title')
	Pakket bewerken
@endsection

@section('content')
<!-- Dit is het formulier voor het bewerken van een actie -->


<h1>Pakket bewerken</h1>

<hr/>

@include ('errors.list')

{!! Form::model($eventPackage, ['method' => 'PATCH', 'url' => 'event-packages/' . $eventPackage->id ]) !!}

	@include ('event-packages.form')
	
{!! Form::close() !!}

@endsection