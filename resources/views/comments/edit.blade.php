@extends('master')

@section('title')
Opmerking bewerken
@endsection

@section('content')

<h1>Opmerking bewerken</h1>
<h4>voor ...</h4>

<hr />

@include ('errors.list')

{!! Form::open(['url' => 'comments/'.$comment->id, 'method' => 'PATCH']) !!}

@include ('comments.form')

{!! Form::close() !!}

@endsection