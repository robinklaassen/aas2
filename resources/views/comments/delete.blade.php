@extends('master')

@section('title')
Opmerking verwijderen
@endsection

@section('content')

<h1>Opmerking verwijderen</h1>

<hr />

@include ('errors.list')

{!! Form::open(['url' => 'comments/'.$comment->id, 'method' => 'DELETE']) !!}

<p>Weet je zeker dat je de opmerking bij {{$comment->entityDescription}} wilt verwijderen?</p>

<div class="row">
    <div class="col-sm-6 form-group">
        {!! Form::submit('Verwijderen', ['class' => 'btn btn-danger form-control']) !!}
    </div>
</div>
{!! Form::close() !!}

@endsection