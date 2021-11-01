@extends('master')

@section('title')
Punten
@endsection

@section('content')
<!-- Dit is de overzichtspagina voor het puntensysteem -->

<!-- Titelbalk met knoppen -->
<div class="row">
	<div class="col-sm-6">
		<h1>Punten</h1>
	</div>
	<div class="col-sm-6">
		<p class="text-right">
			@can("create", \App\Models\Action::class)
			<a class="btn btn-primary" type="button" href="{{ url('actions/create') }}" style="margin-top:21px;">
				Nieuwe actie
			</a>
			@endcan
		</p>
	</div>
</div>

<hr />

<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active">
		<a href="#actions" aria-controls="actions" role="tab" data-toggle="tab">Lijst van acties</a>
	</li>
	<li role="presentation"><a href="#points" aria-controls="points" role="tab" data-toggle="tab">Huidige stand</a></li>
</ul>

<div class="tab-content" style="margin-top:20px;">
	<div role="tabpanel" class="tab-pane active" id="actions">
		<div class="row">
			<div class="col-sm-9">
				<!-- Actiestabel -->
				<table class="table table-hover" id="actionsTable" data-page-length="25">
					<thead>
						<tr>
							<th>Datum</th>
							<th>Lid</th>
							<th>Omschrijving</th>
							<th>Punten</th>
							<th></th>
							<th></th>
						</tr>
					</thead>

					<tbody>
						@foreach ($actions as $action)
						<tr>
							<td>{{ $action->date->format('Y-m-d') }}</td>
							<td>
								<a href="{{ url('/members', $action->member->id) }}">{{ $action->member->volnaam }}</a>
							</td>
							<td>{{ $action->description }}</td>
							<td>{{ $action->points }}</td>
							<td>
								@can("update", $action)
								<a href="{{ url('/actions', [$action->id, 'edit']) }}">
									<span class="glyphicon glyphicon-edit" data-toggle="tooltip"
										title="Bewerken"></span>
								</a>
								@endcan
							</td>
							<td>
								@can("delete", $action)
								<a href="{{ url('/actions', [$action->id, 'delete']) }}">
									<span class="glyphicon glyphicon-remove" data-toggle="tooltip"
										title="Verwijderen"></span>
								</a>
								@endcan
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div role="tabpanel" class="tab-pane" id="points">
		<div class="row">
			<div class="col-sm-5">
				<!-- Actiestabel -->
				<table class="table table-hover" id="pointsTable">
					<thead>
						<tr>
							<th>Lid</th>
							<th>Punten</th>
							<th>Level</th>
						</tr>
					</thead>

					<tbody>
						@foreach ($members as $member)
						<tr>
							<td><a href="{{ url('/members', $member->id) }}">{{ $member->volnaam }}</a></td>
							<td>{{ $member->points }}</td>
							<td>{{ $member->rank }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@endsection

@section('footer')
<script type="text/javascript">
	$( document ).ready(function() {
    $('#actionsTable').DataTable({
		responsive: true,
		order: [[ 0, "desc" ]],
		columns: [
			null,
			null,
			null,
			null,
			{'orderable':false},
			{'orderable':false}
		]
	});

	$('#pointsTable').DataTable({
		paging: false,
		order: [[ 1, "desc" ]],
		columns: [
			null,
			null,
			null
		]
	});
});
</script>
@endsection
