@extends('master')

@section('title')
	{{ $participant->voornaam }} op {{ $event->naam }}
@endsection

@section('content')
<!-- Dit is het formulier voor het bewerken van een deelnemer van een evenement -->


<h1>{{ $participant->voornaam }} op {{ $event->naam }}</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'events/'.$event->id.'/edit-participant/'.$participant->id, 'method' => 'PUT']) !!}

<!--
<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('selected_courses', 'Vakken:') !!}
		{!! Form::select('selected_courses[]', $course_options, $retrieved_courses, ['multiple',  'id' => 'courseSelect', 'class' => 'form-control']) !!}
	</div>
</div>
-->

<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('datum_betaling', 'Datum betaling:') !!}
		{!! Form::input('date', 'datum_betaling', $participant->pivot->datum_betaling, ['class' => 'form-control', 'placeholder' => 'Format: jjjj-mm-dd']) !!}
	</div>
	<div class="col-sm-2 form-group">
		{!! Form::label('geplaatst', 'Geplaatst?') !!}<br/>
		{!! Form::hidden('geplaatst', 0) !!}
		{!! Form::checkbox('geplaatst', 1, $participant->pivot->geplaatst, ['style' => 'margin-top:14px;']) !!}
	</div>
</div>

<hr/>

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
	<div class="col-sm-4 form-group">
		{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
	</div>
</div>
{!! Form::close() !!}

@endsection