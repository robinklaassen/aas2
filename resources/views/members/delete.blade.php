@extends('master')

@section('title')
	Lid verwijderen
@endsection


@section('content')
<!-- Dit is het formulier voor het verwijderen van een lid -->


<h1>Lid verwijderen</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'members/'.$member->id, 'method' => 'DELETE']) !!}

	<p>Weet je zeker dat je het lid {{ $member->voornaam }} {{ $member->tussenvoegsel }} {{ $member->achternaam }} wil verwijderen? Hiermee worden alle gegevens en alle koppelingen met andere tabellen onherroepelijk gewist!</p>
	
	<div class="row">
		<div class="col-sm-6 form-group">
			{!! Form::submit('Verwijderen', ['class' => 'btn btn-danger form-control']) !!}
		</div>
	</div>
{!! Form::close() !!}

@endsection