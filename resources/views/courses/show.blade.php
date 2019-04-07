@extends('master')

@section('title')
{{ $course->naam }}
@endsection

@section('content')
<!-- Dit is de pagina met gegevens voor een specifiek vak -->

<!-- Volledige naam en knoppen -->
<div class="row">
	<div class="col-sm-6">
		<h1>{{ $course->naam }} ({{ $course->code }})</h1>
	</div>
	<div class="col-sm-6">
		<p class="text-right">
			<a class="btn btn-primary" type="button" href="{{ url('/courses', [$course->id, 'edit']) }}" style="margin-top:21px;">Bewerken</a>
			<a class="btn btn-danger" type="button" href="{{ url('/courses', [$course->id, 'delete']) }}" style="margin-top:21px;">Verwijderen</a>
		</p>
	</div>
</div>

<hr/>
<div class="row">
	<div class="col-sm-6">
		<table class="table table-hover" id="courseTable">
			<caption>Leden met dit vak ({{ $course->members()->where('soort', '!=', 'oud')->count() }})</caption>
			<thead>
				<tr>
					<th>Naam</th>
					<th>Niveau</th>
					<th>Soort lid</th>
					<th>VOG</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($course->members()->where('soort','!=','oud')->get() as $member)
			<tr>
				<td><a href="{{ url('/members', $member->id) }}">{{ $member->voornaam }} {{ $member->tussenvoegsel }} {{ $member->achternaam }}</a></td>
				<td>{{ $member->pivot->klas }}</td>
				<td>{{ $member->soort }}</td>
				<td>
					@if ($member->vog)
						<span style="display:none;">1</span>
						<span class="glyphicon glyphicon-ok"></span>
					@else
						<span style="display:none;">0</span>	
						<span class="glyphicon glyphicon-remove"></span>
					@endif
				</td>
			</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection

@section ('footer')
<script type="text/javascript">
($(document).ready(function(){
	$('#courseTable').DataTable({
		paging: false
	});
}));
</script>
@endsection