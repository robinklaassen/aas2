@extends('master')

@section('title')
	Actie bewerken
@endsection

@section('content')
<!-- Dit is het formulier voor het bewerken van een actie -->


<h1>Actie bewerken</h1>

<hr/>

@include ('errors.list')

{!! Form::model($action, ['method' => 'PATCH', 'url' => 'actions/'.$action->id ]) !!}

	@include ('actions.form')
	
{!! Form::close() !!}

@endsection