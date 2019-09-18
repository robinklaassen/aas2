@extends('master')

@section('title')
Informatie verwijderen
@endsection

@section('content')

<h1>Informatie verwijderen</h1>

<hr />

@include ('errors.list')

{!! Form::open(['url' => 'events/'.$comment->id, 'method' => 'DELETE']) !!}

<p>Weet je zeker dat je de extra informatie bij {{$comment->entityDescription}} wilt verwijderen?</p>

<div class="row">
    <div class="col-sm-6 form-group">
        {!! Form::submit('Verwijderen', ['class' => 'btn btn-danger form-control']) !!}
    </div>
</div>
{!! Form::close() !!}

@endsection