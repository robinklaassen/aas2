@extends('master')

@section('title')
	Deelnemer verwijderen
@endsection

@section('content')
<!-- Dit is het formulier voor het verwijderen van een deelnemer -->


<h1>Deelnemer verwijderen</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'participants/'.$participant->id, 'method' => 'DELETE']) !!}

	<p>Weet je zeker dat je de deelnemer {{ $participant->voornaam }} {{ $participant->tussenvoegsel }} {{ $participant->achternaam }} wil verwijderen? Hiermee worden alle gegevens en alle koppelingen met andere tabellen onherroepelijk gewist!</p>
	
	<div class="row">
		<div class="col-sm-6 form-group">
			{!! Form::submit('Verwijderen', ['class' => 'btn btn-danger form-control']) !!}
		</div>
	</div>
{!! Form::close() !!}

@endsection