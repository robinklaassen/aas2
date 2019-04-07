@extends('master')

@section('title')
	Evenement bewerken
@endsection

@section('content')
<!-- Dit is het formulier voor het bewerken van een evenement -->


<h1>Evenement bewerken</h1>

<hr/>

@include ('errors.list')

{!! Form::model($event, ['method' => 'PATCH', 'url' => 'events/'.$event->id ]) !!}

	@include ('events.form')
	
{!! Form::close() !!}

@endsection