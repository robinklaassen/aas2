@extends('master')

@section('title')
	{{ $participant->voornaam }} op {{ $event->naam }}
@endsection

@section('content')
<!-- Dit is het formulier voor het bewerken van de vakken van een deelnemer op kamp -->

<h1>{{ $participant->voornaam }} op {{ $event->naam }}</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'participants/'.$participant->id.'/edit-event/'.$event->id, 'method' => 'PUT']) !!}

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