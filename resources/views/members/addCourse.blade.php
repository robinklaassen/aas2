@extends('master')

@section('title')
	Vak toevoegen
@endsection


@section('content')
<!-- Dit is het formulier voor het toevoegen van een nieuw vak aan een lid -->

@if ($viewType == 'admin')
	<h1>Vak toevoegen voor {{ $member->voornaam }}</h1>
@elseif ($viewType == 'profile')
	<h1>Nieuw vak toevoegen</h1>
@endif

<hr/>

@include ('errors.list')

@if ($viewType == 'admin')
	{!! Form::open(['url' => 'members/'.$member->id.'/add-course', 'method' => 'PUT']) !!}
@elseif ($viewType == 'profile')
	{!! Form::open(['url' => 'profile/add-course', 'method' => 'PUT']) !!}
@endif


<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('selected_course', 'Vak:') !!}
		{!! Form::select('selected_course', $course_options, null, ['id' => 'selectCourse', 'class' => 'form-control']) !!}
	</div>
	
	<div class="col-sm-2 form-group">
		{!! Form::label('klas', 'Klas:') !!}
		{!! Form::input('number', 'klas', null, ['class' => 'form-control', 'min' => '1', 'max' => '6', 'step' => '1']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-6 form-group">
		{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
	</div>
</div>

{!! Form::close() !!}

@endsection