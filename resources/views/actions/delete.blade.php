@extends('master')

@section('title')
	Actie verwijderen
@endsection

@section('content')
<!-- Dit is het formulier voor het verwijderen van een actie -->


<h1>Actie verwijderen</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'actions/'.$action->id, 'method' => 'DELETE']) !!}

	<p>Weet je zeker dat je de actie '{{ $action->description }}' van {{ $action->voornaam }} {{ $action->tussenvoegsel }} {{ $action->achternaam }} wil verwijderen? Hiermee worden alle gegevens onherroepelijk gewist!</p>
	
	<div class="row">
		<div class="col-sm-6 form-group">
			{!! Form::submit('Verwijderen', ['class' => 'btn btn-danger form-control']) !!}
		</div>
	</div>
{!! Form::close() !!}

@endsection