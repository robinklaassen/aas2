@extends('master')

@section('title')
	Deelnemer van evenement verwijderen
@endsection


@section('content')
<!-- Dit is het formulier voor het verwijderen van een deelnemer van een evenement -->


<h1>Deelnemer van evenement verwijderen</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'events/'.$event->id.'/remove-participant/'.$participant->id, 'method' => 'DELETE']) !!}

	<p>Weet je zeker dat je {{ $participant->voornaam }} {{ $participant->tussenvoegsel }} {{ $participant->achternaam }} van het evenement {{ $event->naam }} ({{ $event->code }}) wil verwijderen? Zowel de deelnemer als het kamp blijft gewoon bestaan.</p>
	
	<div class="row">
		<div class="col-sm-6 form-group">
			{!! Form::submit('Verwijderen', ['class' => 'btn btn-danger form-control']) !!}
		</div>
	</div>
{!! Form::close() !!}

@endsection