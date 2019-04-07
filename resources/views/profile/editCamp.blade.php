@extends('master')

@section('title')
	Vakken op {{ $event->naam }}
@endsection

@section('content')
<!-- Dit is het formulier voor het bewerken van de vakken op kamp, vanuit je deelnemerprofiel -->

<h1>Vakken bewerken voor {{ $event->naam }}</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'profile/edit-camp/'.$event->id, 'method' => 'PUT']) !!}

<div class="row">
	<div class="col-sm-9">
		<div class="well">
			Hieronder kunt de vakken waar op kamp aandacht aan moet worden besteed, opgeven. Bij ieder vak kunt u in het tekstvak ernaast extra informatie opgeven, zoals de onderwerpen en moeilijkheden in kwestie.
		</div>
	</div>
</div>
	
<p><b>Vakken:</b></p>

@for ($i = 0; $i < 6; $i++)
<div class="row">
	<div class="col-sm-3 form-group">
		<select class="form-control" name="vak[]">
			@foreach ($course_options as $id => $course)
				<option value="{{$id}}" @if (array_key_exists($i,$retrieved_courses) && $retrieved_courses[$i]['id'] == $id) selected @endif>{{$course}}</option>
			@endforeach
		</select>
	</div>
	
	<div class="col-sm-6 form-group">
		<textarea class="form-control" name="vakinfo[]">@if (array_key_exists($i,$retrieved_courses)) {{ $retrieved_courses[$i]['info'] }} @endif</textarea>
	</div>
</div>
@endfor

<div class="row">
	<div class="col-sm-9">
		<div class="well">
			Anderwijs wordt per automatische email op de hoogte gesteld wanneer u de wijzigingen opslaat. Heeft u niets gewijzigd, gebruik dan de 'terug'-knop in uw browser.
		</div>
		<div class="form-group">
			{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
		</div>
	</div>
</div>

{!! Form::close() !!}

@endsection