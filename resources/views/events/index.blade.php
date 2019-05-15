@extends('master')

@section('title')
	Evenementen
@endsection

@section('content')
<!-- Dit is de overzichtspagina voor evenementen -->

<!-- Titelbalk met knoppen -->
<div class="row">
	<div class="col-sm-6">
		<h1>Evenementen</h1>
	</div>
	<div class="col-sm-6">
		<p class="text-right">
			<a class="btn btn-primary" type="button" href="{{ url('events/create') }}" style="margin-top:21px;">Nieuw evenement</a>
		</p>
	</div>
</div>

<hr/>

<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#kampen" aria-controls="kampen" role="tab" data-toggle="tab">Kampen</a></li>
	<li role="presentation"><a href="#trainingen" aria-controls="trainingen" role="tab" data-toggle="tab">Trainingen</a></li>
	<li role="presentation"><a href="#overig" aria-controls="overig" role="tab" data-toggle="tab">Overig</a></li>
</ul>

<div class="tab-content" style="margin-top:20px;">
	<div role="tabpanel" class="tab-pane active" id="kampen">
		<!-- Kampentabel -->
		<table class="table table-hover" id="campsTable" data-page-length="25">
			<thead>
				<tr>
					<th>Naam</th>
					<th>Code</th>
					<th>Start kamp</th>
					<th>Eind kamp</th>
					<th>Locatie</th>
					<th>Deelnemers</th>
					<th>Leiding</th>
				</tr>
			</thead>

			<tbody>
				@foreach ($events as $event)
					<tr>
						<td><a href="{{ url('/events', $event->id) }}">{{ $event->naam }}</a></td>
						<td>{{ $event->code }}</td>
						<td>{{ $event->datum_start->toDateString() }}</td>
						<td>{{ $event->datum_eind->toDateString() }}</td>
						<td>{{ $event->location->plaats }}</td>
						<td>{{ $event->participants->count() }}</td>
						<td>{{ $event->members->count() }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	<div role="tabpanel" class="tab-pane" id="trainingen">
		<!-- Trainingentabel -->
		<table class="table table-hover" id="trainingsTable" data-page-length="25">
			<thead>
				<tr>
					<th>Naam</th>
					<th>Code</th>
					<th>Start training</th>
					<th>Eind training</th>
					<th>Locatie</th>
					<th>Aantal trainers</th>
				</tr>
			</thead>

			<tbody>
				@foreach ($trainings as $training)
					<tr>
						<td><a href="{{ url('/events', $training->id) }}">{{ $training->naam }}</a></td>
						<td>{{ $training->code }}</td>
						<td>{{ $training->datum_start->toDateString() }}</td>
						<td>{{ $training->datum_eind->toDateString() }}</td>
						<td>{{ $training->location->plaats }}</td>
						<td>{{ $training->members->count() }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	<div role="tabpanel" class="tab-pane" id="overig">
		<!-- Overige activiteiten -->
		<table class="table table-hover" id="othersTable" data-page-length="25">
			<thead>
				<tr>
					<th>Naam</th>
					<th>Code</th>
					<th>Start evenement</th>
					<th>Eind evenement</th>
					<th>Locatie</th>
					<th>Aantal leden</th>
				</tr>
			</thead>

			<tbody>
				@foreach ($others as $other)
					<tr>
						<td><a href="{{ url('/events', $other->id) }}">{{ $other->naam }}</a></td>
						<td>{{ $other->code }}</td>
						<td>{{ $other->datum_start->toDateString() }}</td>
						<td>{{ $other->datum_eind->toDateString() }}</td>
						<td>{{ $other->location->plaats }}</td>
						<td>{{ $other->members->count() }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

@endsection

@section('footer')
<script type="text/javascript">
$( document ).ready(function() {
    $('#campsTable').DataTable({
		order: [[ 2, "desc" ]],
		columns: [
			null,
			null,
			null,
			{'orderable':false},
			null,
			{'orderable':false},
			{'orderable':false}
		]
	});

	$('#trainingsTable').DataTable({
		order: [[ 2, "desc" ]],
		columns: [
			null,
			null,
			null,
			{'orderable':false},
			null,
			{'orderable':false}
		]
	});

	$('#othersTable').DataTable({
		order: [[ 2, "desc" ]],
		columns: [
			null,
			null,
			null,
			{'orderable':false},
			null,
			{'orderable':false}
		]
	});
});
</script>
@endsection