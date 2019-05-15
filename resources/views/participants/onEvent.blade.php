@extends('master')

@section('title')
	{{ $participant->voornaam }} op kamp
@endsection

@section('content')
<!-- Dit is het formulier voor het op kamp sturen van een deelnemer -->

<h1>Stuur {{ $participant->voornaam }} op kamp</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'participants/'.$participant->id.'/on-event', 'method' => 'PUT']) !!}

<div class="row">
	<div class="col-sm-3 form-group">
		{!! Form::label('selected_event', 'Kamp:') !!}
		{!! Form::select('selected_event', $event_options, null, ['class' => 'form-control']) !!}
	</div>
</div>

<p><b>Vakken:</b></p>

@for ($i = 0; $i < 6; $i++)
<div class="row">
	<div class="col-sm-3 form-group">
		<select class="form-control" name="vak[]">
			@foreach ($course_options as $id => $course)
				<option value="{{$id}}">{{$course}}</option>
			@endforeach
		</select>
	</div>
	
	<div class="col-sm-6 form-group">
		<textarea class="form-control" name="vakinfo[]"></textarea>
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