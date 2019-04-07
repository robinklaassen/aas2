@extends('master')

@section('title')
	{{ $member->voornaam }} op {{ $event->naam }}
@endsection

@section('content')
<!-- Dit is het formulier voor het bewerken van een leiding van een evenement -->


<h1>{{ $member->voornaam }} op {{ $event->naam }}</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'events/'.$event->id.'/edit-member/'.$member->id, 'method' => 'PUT']) !!}

<div class="row">
	<div class="col-sm-2 form-group">
		{!! Form::label('wissel', 'Wisselleiding?') !!}<br/>
		{!! Form::hidden('wissel', 0) !!}
		{!! Form::checkbox('wissel', 1, $member->pivot->wissel, ['style' => 'margin-top:14px;']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('wissel_datum_start', 'Wissel datum start:') !!}
		{!! Form::input('date', 'wissel_datum_start', $member->pivot->wissel_datum_start, ['class' => 'form-control', 'placeholder' => 'Format: jjjj-mm-dd']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('wissel_datum_eind', 'Wissel datum eind:') !!}
		{!! Form::input('date', 'wissel_datum_eind', $member->pivot->wissel_datum_eind, ['class' => 'form-control', 'placeholder' => 'Format: jjjj-mm-dd']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
	</div>
</div>
{!! Form::close() !!}

@endsection