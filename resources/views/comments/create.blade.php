@extends('master')

@section('title')
Nieuwe opmerking
@endsection

@section('content')


<h1>Nieuw opmerking</h1>
<h4>voor {{\App\Comment::getEntityDescriptionByKey($type, $key)}}</h4>

<hr />

@include ('errors.list')
@php
$qs = 'origin=' . urlencode($origin);
@endphp
{!! Form::open(['url' => 'comments?'. $qs , 'method' => 'POST']) !!}

{!! Form::hidden('entity_type', $type) !!}
{!! Form::hidden('entity_id', $key) !!}

@include ('comments.form')

{!! Form::close() !!}

@endsection