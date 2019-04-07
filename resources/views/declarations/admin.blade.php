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
	<!--
	<div class="col-sm-6">
		<p class="text-right">
			<a class="btn btn-info" type="button" href="{{ url('declarations/export') }}" style="margin-top:21px;">Exporteren</a>
		</p>
	</div>
	-->
</div>

<hr/>

<p class="text-right">
	Totaal openstaand bedrag: <strong>{{ formatPrice($total_open) }}</strong>
</p>

<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#declarations_open" aria-controls="declarations_open" role="tab" data-toggle="tab">Openstaand</a></li>
	<li role="presentation"><a href="#declarations_to_process" aria-controls="declarations_to_process" role="tab" data-toggle="tab">Verwerken</a></li>
	<li role="presentation"><a href="#declarations_closed" aria-controls="declarations_closed" role="tab" data-toggle="tab">Afgesloten</a></li>
</ul>

<div class="tab-content" style="margin-top:20px;">
	<div role="tabpanel" class="tab-pane active" id="declarations_open">
		<!-- Openstaande declaraties -->
		<table class="table table-hover" id="declarationsOpenTable" data-page-length="25">
			<thead>
				<tr>
					<th>Datum</th>
					<th>Lid</th>
					<th>Bestand</th>
					<th>Omschrijving</th>
					<th>Bedrag</th>
					<th>Gift</th>
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
							@unless ($declaration->filename === null)
								<a href="{{ asset('uploads/declarations/' . $declaration->member->id . '/' . $declaration->filename) }}" target="_blank">{{$declaration->filename}}</a>
							@else
								-
							@endunless
						</td>
						<td>{{ $declaration->description }}</td>
						<td>{{ formatPrice($declaration->amount) }}</td>
						<td>{{ ($declaration->gift ? 'Ja' : 'Nee') }}</td>
						<td><a href="{{ url('/declarations', [$declaration->id, 'edit']) }}"><span class="glyphicon glyphicon-edit" data-toggle="tooltip" title="Bewerken"></span></a></td>
						<td><a href="{{ url('/declarations', [$declaration->id, 'delete']) }}"><span class="glyphicon glyphicon-remove" data-toggle="tooltip" title="Verwijderen"></span></a></td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	
	<div role="tabpanel" class="tab-pane" id="declarations_to_process">
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
						@foreach ($members as $member)
							<tr>
								<td>{{ formatPrice($member->declarations()->open()->where('gift', 0)->sum('amount')) }}</td>
								<td>{{ $member->iban }}</td>
								<td><a href="{{ url('/members', $member->id) }}">{{ $member->voornaam }} {{ $member->tussenvoegsel }} {{ $member->achternaam }}</a></td>
								<td><a href="{{ url('/declarations', ['process', $member->id]) }}"><span class="glyphicon glyphicon-check"></a></span></td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	<div role="tabpanel" class="tab-pane" id="declarations_closed">
		<!-- Declaratietabel -->
		<table class="table table-hover" id="declarationsClosedTable" data-page-length="25">
			<thead>
				<tr>
					<th>Datum</th>
					<th>Lid</th>
					<th>Bestand</th>
					<th>Omschrijving</th>
					<th>Bedrag</th>
					<th>Gift</th>
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
							@unless ($declaration->filename === null)
								<a href="{{ asset('uploads/declarations/' . $declaration->member->id . '/' . $declaration->filename) }}" target="_blank">{{$declaration->filename}}</a>
							@else
								-
							@endunless
						</td>
						<td>{{ $declaration->description }}</td>
						<td>{{ formatPrice($declaration->amount) }}</td>
						<td>{{ ($declaration->gift ? 'Ja' : 'Nee') }}</td>
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