@extends('master')

@section('title')
	Lid zoeken op vakdekking
@endsection

@section('header')
<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
@endsection


@section('content')


<h1>Lid zoeken op vakdekking</h1>

<hr/>

@include ('errors.list')

<form class="form-inline" method="get" action="#">

	<div class="form-group">
		<label for="courses">Vakken: </label>
		<select class="form-control" name="courses[]" id="courses" multiple>
			@foreach ($courseList as $id => $course)
				<option value="{{$id}}" @if (in_array($id, $courses)) selected @endif>{{$course}}</option>
			@endforeach
		</select>
	</div>
	
	<!--<input type="hidden" name="vog" value="0">-->
	@can("showAnyAdministrative", \App\Member::class)
	<div class="checkbox" style="margin: 0 20px;">
		<label>
			<input type="checkbox" name="vog" id="vog" value="1" checked> Moet VOG hebben
		</label>
	</div>
	@endcan
	
	<button type="submit" class="btn btn-primary">Zoeken</button>
	
</form>

@if ($courses != [])

<hr/>

<table class="table table-hover">
	<thead>
		<tr>
			<th>Naam</th>
			<th>Soort lid</th>
			@can("showAnyAdministrative", \App\Member::class)
			<th>VOG</th>
			@endcan
			@foreach ($courses as $course_id)
				<th>{{$courseCodes[$course_id]}}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@forelse ($members as $member)
			<tr>
				<td><a href="{{ url('/members', $member->id) }}">{{$member->voornaam}} {{$member->tussenvoegsel}} {{$member->achternaam}}</a></td>
				<td>{{$member->soort}}</td>
				@can("showAnyAdministrative", \App\Member::class)
				<td>
					@if ($member->vog)
						<span style="display:none;">1</span>
						<span class="glyphicon glyphicon-ok"></span>
					@else
						<span style="display:none;">0</span>	
						<span class="glyphicon glyphicon-remove"></span>
					@endif
				</td>
				@endcan
				@foreach ($courses as $course_id)
					<td>{{$level[$member->id][$course_id]}}</td>
				@endforeach
			</tr>
		@empty
			Geen leden gevonden
		@endforelse
	</tbody>
</table>

@endif

@endsection

@section('footer')
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#courses").select2();
});
</script>
@endsection