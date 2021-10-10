@extends('master')

@section('title')
	Dashboard declaraties
@endsection

@section('content')
<!-- Dit is de adminpagina voor alle declaraties -->

<!-- Titelbalk met knoppen -->
<div class="row">
	<div class="col-sm-6">
		<h1>Dashboard declaraties</h1>
	</div>
</div>

<hr/>

<p class="text-right">
	Totaal openstaand bedrag: <strong>@money($total_open)</strong>
</p>

<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#declarations_to_process" aria-controls="declarations_to_process" role="tab" data-toggle="tab">Overzicht</a></li>
	<li role="presentation"><a href="#declarations_open" aria-controls="declarations_open" role="tab" data-toggle="tab">Alle Openstaand</a></li>
	<li role="presentation"><a href="#declarations_closed" aria-controls="declarations_closed" role="tab" data-toggle="tab">Alle Afgesloten</a></li>
</ul>

<div class="tab-content" style="margin-top:20px;">

	<div role="tabpanel" class="tab-pane active" id="declarations_to_process">
		<div class="row">
			<div class="col-md-8">
				<!-- Verwerkingstabel -->
				<table class="table table-hover" id="declarationsToProcessTable" data-page-length="25">
					<thead>
						<tr>
							<th>Bedrag</th>
							<th>Rekeningnummer</th>
							<th>Ten name van</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@foreach ($openDeclarations as $declaration)
							<tr>
								<td>@money($declaration->amount)</td>
								<td>
									@if($declaration->declaration_type === 'pay')
										{{ $declaration->iban }}
									@else
										{{ __('declaration-types.' . $declaration->declaration_type) }}
									@endif
								</td>
								<td><a href="{{ url('/members', $declaration->member_id) }}">{{ $declaration->voornaam }} {{ $declaration->tussenvoegsel }} {{ $declaration->achternaam }}</a></td>
								<td>
									@can('process', \App\Declaration::class)
									<a href="{{ url('/declarations', ['process', $declaration->id, $declaration->declaration_type]) }}">
										<span class="glyphicon glyphicon-check"></span>
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

	<div role="tabpanel" class="tab-pane" id="declarations_open">
		<!-- Openstaande declaraties -->
		<table class="table table-hover" id="declarationsOpenTable" data-page-length="25" width="100%">
			<thead>
				<tr>
					<th>Datum</th>
					<th>Lid</th>
					<th>Bestand</th>
					<th>Omschrijving</th>
					<th>Bedrag</th>
					<th>Actie</th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</thead>

			<tbody>
				@foreach (App\Declaration::open()->get() as $declaration)
					<tr>
						<td>{{ $declaration->date->format('Y-m-d') }}</td>
						<td>
							<a href="{{ url('/members', $declaration->member->id) }}">{{ $declaration->member->voornaam }} {{ $declaration->member->tussenvoegsel }} {{ $declaration->member->achternaam }}</a>
						</td>
						<td>
							@if ($declaration->filename)
								<a href="{{ url('declarations/' . $declaration->id, 'file') }}" target="_blank">{{$declaration->original_filename}}</a>
							@else
								-
							@endif
						</td>
						<td>{{ $declaration->description }}</td>
						<td>@money($declaration->amount)</td>
						<td>{{ __('declaration-types.' . $declaration->declaration_type) }}</td>
						<td><a href="{{ url('/declarations', [$declaration->id, 'edit']) }}"><span class="glyphicon glyphicon-edit" data-toggle="tooltip" title="Bewerken"></span></a></td>
						<td><a href="{{ url('/declarations', [$declaration->id, 'delete']) }}"><span class="glyphicon glyphicon-remove" data-toggle="tooltip" title="Verwijderen"></span></a></td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	<div role="tabpanel" class="tab-pane" id="declarations_closed">
		<!-- Declaratietabel -->
		<table class="table table-hover" id="declarationsClosedTable" data-page-length="25" width="100%">
			<thead>
				<tr>
					<th>Datum</th>
					<th>Lid</th>
					<th>Bestand</th>
					<th>Omschrijving</th>
					<th>Bedrag</th>
					<th>Type</th>
					<th>Afgehandeld op</th>
					<th></th>
					<th></th>
				</tr>
			</thead>

			<tbody>
				@foreach (App\Declaration::closed()->get() as $declaration)
					<tr>
						<td>{{ $declaration->date->format('Y-m-d') }}</td>
						<td>
							<a href="{{ url('/members', $declaration->member->id) }}">{{ $declaration->member->voornaam }} {{ $declaration->member->tussenvoegsel }} {{ $declaration->member->achternaam }}</a>
						</td>
						<td>
							@if ($declaration->filename)
								<a href="{{ url('declarations/' . $declaration->id, 'file') }}" target="_blank">{{$declaration->original_filename}}</a>
							@else
								-
							@endif
						</td>
						<td>{{ $declaration->description }}</td>
						<td>@money($declaration->amount)</td>
						<td>{{ __('declaration-types.' . $declaration->declaration_type) }}</td>
						<td>{{ $declaration->closed_at->format('Y-m-d') }}</td>
						<td><a href="{{ url('/declarations', [$declaration->id, 'edit']) }}"><span class="glyphicon glyphicon-edit" data-toggle="tooltip" title="Bewerken"></span></a></td>
						<td><a href="{{ url('/declarations', [$declaration->id, 'delete']) }}"><span class="glyphicon glyphicon-remove" data-toggle="tooltip" title="Verwijderen"></span></a></td>
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
	$('#declarationsOpenTable').DataTable({
		responsive: true,
		order: [[ 0, "desc" ]],
		columns: [
			null,
			null,
			{'orderable':false},
			null,
			null,
			null,
			{'orderable':false},
			{'orderable':false}
		]
	});

    $('#declarationsClosedTable').DataTable({
		responsive: true,
		order: [[ 0, "desc" ]],
		columns: [
			null,
			null,
			{'orderable':false},
			null,
			null,
			null,
			null,
			{'orderable':false},
			{'orderable':false}
		]
	});
});
</script>
@endsection
