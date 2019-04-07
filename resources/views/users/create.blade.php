@extends('master')

@section('title')
	Nieuwe gebruiker
@endsection

@section('content')
<!-- Dit is het formulier voor het aanmaken van een nieuwe gebruiker -->


<h1>Nieuwe gebruiker ({{ ($type == 'member') ? 'lid' : 'deelnemer' }})</h1>

<hr/>

@include ('errors.list')

@if ($type == 'member')
	{!! Form::open(['url' => 'users/create-for-member']) !!}
@elseif ($type == 'participant')
	{!! Form::open(['url' => 'users/create-for-participant']) !!}
@endif

<div class="row">
	<div class="col-sm-4">
	
		<div class="form-group">
			{!! Form::label('profile_id', 'Profiel:') !!}
			{!! Form::select('profile_id', $profile_options, null, ['class' => 'form-control']) !!}
		</div>

		@if ($type == 'member')
		<div class="form-group">
			<label for="is_admin">Admin:</label>
			<select class="form-control" name="is_admin">
				<option value="0">Nee</option>
				<option value="1">Ja</option>
				@if (\Auth::user()->is_admin == 2)
					<option value="2">Ja +</option>
				@endif
			</select>
		</div>
		@endif
		
		<div class="form-group">
			{!! Form::submit('Aanmaken', ['class' => 'btn btn-primary form-control']) !!}
		</div>
		
	</div>
	
	<div class="col-sm-8">
		<div class="well" style="margin-top:25px;">
			<strong>Let op! </strong>De gebruiker krijgt automatisch een email met een door het systeem gegenereerde gebruikersnaam en wachtwoord. Je hoeft hier dus verder niets meer voor te doen.
		</div>
	</div>
</div>

{!! Form::close() !!}

@endsection