@extends('master')

@section('title')
	Declaratie bewerken
@endsection

@section('content')
<!-- Dit is het formulier voor het bewerken van een declaratie -->


<h1>Declaratie bewerken</h1>

<hr/>

@include ('errors.list')

{!! Form::model($declaration, ['method' => 'PATCH', 'url' => 'declarations/'.$declaration->id, 'files' => true  ]) !!}

@include ("declarations.form")
	
{!! Form::close() !!}

@endsection