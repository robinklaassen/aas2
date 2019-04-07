@extends('master')

@section('title')
	Gebruiker verwijderen
@endsection

@section('content')
<!-- Dit is het formulier voor het verwijderen van een gebruiker -->


<h1>Gebruiker verwijderen</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'users/'.$user->id, 'method' => 'DELETE']) !!}

	<p>Weet je zeker dat je de gebruiker {{ $user->username }} wil verwijderen? Het lid of de deelnemer in kwestie wordt niet verwijderd.</p>
	
	<div class="row">
		<div class="col-sm-6 form-group">
			{!! Form::submit('Verwijderen', ['class' => 'btn btn-danger form-control']) !!}
		</div>
	</div>
{!! Form::close() !!}

@endsection