@extends('master')

@section('title')
	Nieuwe declaratie
@endsection

@section('header')
<style>
input, select, #row-add {
	margin-bottom: 10px;
}
</style>
@endsection

@section('content')
<!-- Dit is het formulier voor het afmaken van een nieuwe declaratie -->

<h1>Nieuwe declaraties</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'declarations/bulk', 'files' => true ]) !!}

<declarations-form 
	target="{{ url("declarations/create-bulk") }}"
	redirect-target="{{ url("declarations") }}"
	csrf="{{ csrf_token() }}"
></declarations-form>

{!! Form::close() !!}

@endsection
