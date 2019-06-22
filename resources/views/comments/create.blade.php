@extends('master')

@section('title')
Nieuwe opmerking
@endsection

@section('content')


<h1>Nieuw opmerking</h1>
<h4>voor ...</h4>

<hr />

@include ('errors.list')

{!! Form::open(['url' => 'comments', 'method' => 'POST']) !!}

@include ('comments.form')

{!! Form::close() !!}

@endsection