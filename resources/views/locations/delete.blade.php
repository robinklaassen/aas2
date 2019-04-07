@extends('master')

@section('title')
	Locatie verwijderen
@endsection


@section('content')
<!-- Dit is het formulier voor het verwijderen van een locatie -->


<h1>Locatie verwijderen</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'locations/'.$location->id, 'method' => 'DELETE']) !!}

	<p>Weet je zeker dat je de locatie {{ $location->naam }} ({{ $location->plaats }}) wil verwijderen? Hiermee worden alle gegevens en alle koppelingen met andere tabellen onherroepelijk gewist!</p>
	
	<div class="row">
		<div class="col-sm-6 form-group">
			{!! Form::submit('Verwijderen', ['class' => 'btn btn-danger form-control']) !!}
		</div>
	</div>
{!! Form::close() !!}

@endsection