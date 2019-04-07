@extends('master')

@section('title')
	Deelnemer verplaatsen naar ander kamp
@endsection

@section('content')


<h1>{{ $participant->volnaam }} verplaatsen van {{$event->naam}}</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'events/'.$event->id.'/move-participant/'.$participant->id, 'method' => 'PUT']) !!}

	<div class="form-group">
		<label for="members">Kamp: </label>
		<select class="form-control" name="destination" id="destination">
			@foreach ($events as $event)
				<option value="{{$event->id}}">{{ $event->naam }} {{ $event->datum_start->format('Y') }} ({{ $event->location->plaats }})</option>
			@endforeach
		</select>
	</div>
	
	<button type="submit" class="btn btn-primary">Verplaatsen</button>
	
{!! Form::close() !!}

@endsection