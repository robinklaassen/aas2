@extends('master')

@section('title')
	Vak bewerken
@endsection

@section('content')
<!-- Dit is het formulier voor het bewerken van een vak -->


<h1>Vak bewerken</h1>

<hr/>

@include ('errors.list')

{!! Form::model($course, ['method' => 'PATCH', 'url' => 'courses/'.$course->id ]) !!}

	@include ('courses.form')
	
{!! Form::close() !!}

@endsection