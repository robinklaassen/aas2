@extends('master')

@section('title')
Opmerking bewerken
@endsection

@section('content')

<h1>Opmerking bewerken</h1>
<h4>Comment bij {{$comment->entityDescription}}</h4>

<hr />

@include ('errors.list')
@php
$qs = 'origin=' . urlencode($origin);
@endphp
{!! Form::model($comment, ['url' => 'comments/' . $comment->id . '?' . $qs, 'method' => 'PATCH']) !!}

@include ('comments.form')

{!! Form::close() !!}

@endsection