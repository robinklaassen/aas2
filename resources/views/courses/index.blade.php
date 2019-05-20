@extends('master')

@section('title')
	Vakken
@endsection

@section('content')
<!-- Dit is de overzichtspagina voor vakken -->

<!-- Titelbalk met knoppen -->
<div class="row">
	<div class="col-sm-6">
		<h1>Vakken</h1>
	</div>
	<div class="col-sm-6">
		<p class="text-right">
			<a class="btn btn-primary" type="button" href="{{ url('courses/create') }}" style="margin-top:21px;">Nieuw vak</a>
		</p>
	</div>
</div>

<hr/>

<div class="row">
	<div class="col-sm-6">
		<!-- Vakkentabel -->
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Naam</th>
					<th>Code</th>
					<th>Leden</th>
					<th>Deelnemers <span class="glyphicon glyphicon-question-sign" style="margin-left:5px;" data-toggle="tooltip" title="Cumulatief. Wanneer een deelnemer op twee verschillende kampen hetzelfde vak doet, wordt het vak twee keer geteld."></span></th>
				</tr>
			</thead>
			
			<tbody>
				@foreach ($courses as $course)
					<tr>
						<td><a href="{{ url('/courses', $course->id) }}">{{ $course->naam }}</a></td>
						<td>{{ $course->code }}</td>
						<td>{{ $course->members()->where('soort', '!=', 'oud')->count() }}</td>
						<td>{{ (array_key_exists($course->id, $num_participants)) ? $num_participants[$course->id] : 0 }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection