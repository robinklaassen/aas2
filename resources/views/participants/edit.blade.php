@extends('master')

@section('title')
	@if ($viewType == 'admin')
		Deelnemer bewerken
	@elseif ($viewType == 'profile')
		Mijn gegevens bewerken
	@endif
@endsection

@section('content')
<!-- Dit is het formulier voor het bewerken van een deelnemer -->

@if ($viewType == 'admin')
	<h1>Deelnemer bewerken</h1>
@elseif ($viewType == 'profile')
	<h1>Mijn gegevens bewerken</h1>
@endif

<hr/>

@include ('errors.list')

@if ($viewType == 'admin')
	{!! Form::model($participant, ['method' => 'PATCH', 'url' => 'participants/'.$participant->id ]) !!}
@elseif ($viewType == 'profile')
	{!! Form::model($participant, ['method' => 'PATCH', 'url' => 'profile' ]) !!}
@endif



	@include ('participants.form')
	
{!! Form::close() !!}

@endsection