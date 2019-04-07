@extends('master')

@section('title')
	Leden koppelen aan evenement
@endsection

@section('header')
<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
@endsection


@section('content')


<h1>Leden koppelen aan {{$event->naam}}</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'events/'.$event->id.'/join-members', 'method' => 'PUT']) !!}

	<div class="form-group">
		<label for="members">Leden: </label>
		<select class="form-control" name="members[]" id="members" multiple>
			@foreach ($members as $member)
				<option value="{{$member->id}}">{{$member->voornaam}} {{$member->tussenvoegsel}} {{$member->achternaam}}</option>
			@endforeach
		</select>
	</div>
	
	<button type="submit" class="btn btn-primary">Toevoegen</button>
	
{!! Form::close() !!}

@endsection

@section('footer')
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#members").select2();
});
</script>
@endsection