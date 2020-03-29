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
			@can("create", \App\Event::class)
			<a class="btn btn-primary" type="button" href="{{ url('events/create') }}" style="margin-top:21px;">Nieuw evenement</a>
			@endcan
		</p>
	</div>
</div>

<hr/>

<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#kampen" aria-controls="kampen" role="tab" data-toggle="tab">Kampen</a></li>
	<li role="presentation"><a href="#trainingen" aria-controls="trainingen" role="tab" data-toggle="tab">Trainingen</a></li>
	<li role="presentation"><a href="#online" aria-controls="online" role="tab" data-toggle="tab">Online</a></li>
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
				@foreach ($camps as $c)
					<tr>
						<td><a href="{{ url('/events', $c->id) }}">{{ $c->naam }}</a></td>
						<td>{{ $c->code }}</td>
						<td>{{ $c->datum_start->toDateString() }}</td>
						<td>{{ $c->datum_eind->toDateString() }}</td>
						<td>{{ $c->location->plaats }}</td>
						<td>{{ $c->participants->count() }}</td>
						<td>{{ $c->members->count() }}</td>
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
				@foreach ($trainings as $t)
					<tr>
						<td><a href="{{ url('/events', $t->id) }}">{{ $t->naam }}</a></td>
						<td>{{ $t->code }}</td>
						<td>{{ $t->datum_start->toDateString() }}</td>
						<td>{{ $t->datum_eind->toDateString() }}</td>
						<td>{{ $t->location->plaats }}</td>
						<td>{{ $t->members->count() }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	<div role="tabpanel" class="tab-pane" id="online">
		<!-- Online events tabel -->
		<table class="table table-hover" id="onlineEventsTable" data-page-length="25">
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
				@foreach ($onlineEvents as $e)
					<tr>
						<td><a href="{{ url('/events', $e->id) }}">{{ $e->naam }}</a></td>
						<td>{{ $e->code }}</td>
						<td>{{ $e->datum_start->toDateString() }}</td>
						<td>{{ $e->datum_eind->toDateString() }}</td>
						<td>{{ $e->location->plaats }}</td>
						<td>{{ $e->participants->count() }}</td>
						<td>{{ $e->members->count() }}</td>
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
				@foreach ($others as $o)
					<tr>
						<td><a href="{{ url('/events', $o->id) }}">{{ $o->naam }}</a></td>
						<td>{{ $o->code }}</td>
						<td>{{ $o->datum_start->toDateString() }}</td>
						<td>{{ $o->datum_eind->toDateString() }}</td>
						<td>{{ $o->location->plaats }}</td>
						<td>{{ $o->members->count() }}</td>
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

	$('#onlineEventsTable').DataTable({
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