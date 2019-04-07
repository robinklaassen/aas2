@extends('master')

@section('title')
{{ $member->voornaam }} op evenement
@endsection


@section('content')
<!-- Dit is het formulier voor het op evenement sturen van een lid -->

<h1>Stuur {{ $member->voornaam }} op evenement</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'members/'.$member->id.'/on-event', 'method' => 'PUT']) !!}

<div class="row">
	<div class="col-sm-6 form-group">
		{!! Form::label('selected_event', 'Evenement:') !!}
		{!! Form::select('selected_event', $event_options, null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-6 form-group">
		{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
	</div>
</div>

{!! Form::close() !!}

@endsection