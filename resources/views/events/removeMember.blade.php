@extends('master')

@section('title')
	Lid van evenement verwijderen
@endsection


@section('content')
<!-- Dit is het formulier voor het verwijderen van een lid van een evenement -->


<h1>Lid van evenement verwijderen</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'events/'.$event->id.'/remove-member/'.$member->id, 'method' => 'DELETE']) !!}

	<p>Weet je zeker dat je {{ $member->voornaam }} {{ $member->tussenvoegsel }} {{ $member->achternaam }} van het evenement {{ $event->naam }} ({{ $event->code }}) wil verwijderen? Zowel het lid als het evenement blijft gewoon bestaan.</p>
	
	<div class="row">
		<div class="col-sm-6 form-group">
			{!! Form::submit('Verwijderen', ['class' => 'btn btn-danger form-control']) !!}
		</div>
	</div>
{!! Form::close() !!}

@endsection