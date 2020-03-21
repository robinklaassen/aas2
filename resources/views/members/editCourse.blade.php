@extends('master')

@section('title')
	Vak bewerken
@endsection

@section('content')
<!-- Dit is het formulier voor het bewerken van een vak van een lid -->

@if ($viewType == 'admin')
	<h1>Bewerken vak {{ $course->naam }} van {{ $member->voornaam }}</h1>
@elseif ($viewType == 'profile')
	<h1>Bewerken vak {{ $course->naam }}</h1>
@endif
<hr/>

@include ('errors.list')

@if ($viewType == 'admin')
	{!! Form::open(['url' => 'members/'.$member->id.'/edit-course/'.$course->id, 'method' => 'PUT']) !!}
@elseif ($viewType == 'profile')
	{!! Form::open(['url' => 'profile/edit-course/'.$course->id, 'method' => 'PUT']) !!}
@endif



<div class="row">
	
	<div class="col-sm-2 form-group">
		{!! Form::label('klas', 'Klas:') !!}
		{!! Form::input('number', 'klas', $course->pivot->klas, ['class' => 'form-control', 'min' => '1', 'max' => '6', 'step' => '1']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-2 form-group">
		{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
	</div>
</div>

{!! Form::close() !!}

@endsection