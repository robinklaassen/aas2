@extends('master')

@section('title')
	Locatie bewerken
@endsection


@section('content')
<!-- Dit is het formulier voor het bewerken van een lokatie -->


<h1>Locatie bewerken</h1>

<hr/>

@include ('errors.list')

{!! Form::model($location, ['method' => 'PATCH', 'url' => 'locations/'.$location->id ]) !!}

	@include ('locations.form')
	
{!! Form::close() !!}

@endsection