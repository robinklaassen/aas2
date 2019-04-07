@extends('master')

@section('title')
	Alle deelnemers plaatsen
@endsection


@section('content')
<!-- Dit is het formulier voor het plaatsen van alle deelnemers -->


<h1>Alle deelnemers plaatsen</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'events/'.$event->id.'/send', 'method' => 'PUT']) !!}

	<p>Weet je zeker dat je alle deelnemers van het kamp {{ $event->naam }} ({{ $event->code }}) wil plaatsen?</p>
	
	<div class="row">
		<div class="col-sm-6 form-group">
			{!! Form::submit('Gaan met die banaan', ['class' => 'btn btn-primary form-control']) !!}
		</div>
	</div>
{!! Form::close() !!}

@endsection