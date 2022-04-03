@extends('master')

@section('title')
	Nieuw wachtwoord
@endsection

@section('content')
<!-- Dit is het formulier voor het instellen van een nieuw wachtwoord voor een gebruiker -->


<h1>Nieuw wachtwoord @if ($viewType == 'admin') voor {{ $user->username }} @endif</h1>

<hr/>

@include ('errors.list')

@if ($viewType == 'admin')
	{!! Form::open(['method' => 'PUT', 'url' => 'users/'.$user->id.'/password' ]) !!}
@elseif ($viewType == 'profile')
	{!! Form::open(['method' => 'PUT', 'url' => 'profile/password' ]) !!}
@endif

<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('password', 'Wachtwoord:') !!}
		{!! Form::input('password', 'password', null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('password_confirmation', 'Wachtwoord bevestigen:') !!}
		{!! Form::input('password', 'password_confirmation', null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
	</div>
</div>
	
{!! Form::close() !!}

@endsection