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


<h1>Nieuwe declaratie - stap 2</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'declarations/bulk', 'files' => true ]) !!}

<file-dropzone 
    multiple="true"
></file-dropzone>

{!! Form::close() !!}

@endsection
