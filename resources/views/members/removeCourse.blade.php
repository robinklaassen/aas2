@extends('master')

@section('title')
	Vak verwijderen
@endsection


@section('content')
<!-- Dit is het formulier voor het verwijderen van een vak van een lid -->

@if ($viewType == 'admin')
	<h1>Verwijderen vak {{ $course->naam }} van {{ $member->voornaam }}</h1>
@elseif ($viewType == 'profile')
	<h1>Verwijderen vak {{ $course->naam }}</h1>
@endif

<hr/>

@include ('errors.list')

@if ($viewType == 'admin')
	{!! Form::open(['url' => 'members/'.$member->id.'/remove-course/'.$course->id, 'method' => 'DELETE']) !!}
@elseif ($viewType == 'profile')
	{!! Form::open(['url' => 'profile/remove-course/'.$course->id, 'method' => 'DELETE']) !!}
@endif
	
	@if ($viewType == 'admin')
		<p>Weet je zeker dat je het vak {{ $course->naam }} van het lid {{ $member->voornaam }} {{ $member->tussenvoegsel }} {{ $member->achternaam }} wil verwijderen? Zowel het vak als het lid blijft gewoon bestaan.</p>
	@elseif ($viewType == 'profile')
		<p>Weet je zeker dat je het vak {{ $course->naam }} wil verwijderen?</p>
	@endif
	
	<div class="row">
		<div class="col-sm-6 form-group">
			{!! Form::submit('Verwijderen', ['class' => 'btn btn-danger form-control']) !!}
		</div>
	</div>
{!! Form::close() !!}

@endsection