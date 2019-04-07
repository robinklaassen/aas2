@extends('master')

@section('title')
	Admin-rechten wijzigen
@endsection

@section('content')
<!-- Dit is het formulier voor het wijzigen van de admin-rechten van een gebruiker -->


<h1>Admin-rechten van {{$user->username}} wijzigen</h1>

<hr/>

@include ('errors.list')

{!! Form::model($user, ['method' => 'PUT', 'url' => 'users/'.$user->id.'/admin' ]) !!}

<div class="row">
	<div class="col-sm-2 form-group">
		<label for="is_admin">Admin:</label>
		<select class="form-control" name="is_admin">
			<option value="0" @if ($user->is_admin == 0) selected @endif>Nee</option>
			<option value="1" @if ($user->is_admin == 1) selected @endif>Ja</option>
			@if (\Auth::user()->is_admin == 2)
				<option value="2" @if ($user->is_admin == 2) selected @endif>Ja +</option>
			@endif
		</select>
	</div>
</div>

<div class="row">
	<div class="col-sm-2 form-group">
		{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
	</div>
</div>
	
{!! Form::close() !!}

@endsection