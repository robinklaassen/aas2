@extends('master')

@section('title')
	Pakket verwijderen
@endsection

@section('content')
<!-- Dit is het formulier voor het verwijderen van een actie -->


<h1>Pakket verwijderen</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'event-packages/'.$eventPackage->id, 'method' => 'DELETE']) !!}

	<p>Weet je zeker dat je het pakket '{{ $eventPackage->code }}' wil verwijderen? Hiermee worden alle gegevens onherroepelijk gewist!</p>
	
	<div class="row">
		<div class="col-sm-6 form-group">
			{!! Form::submit('Verwijderen', ['class' => 'btn btn-danger form-control']) !!}
		</div>
	</div>

{!! Form::close() !!}

@endsection