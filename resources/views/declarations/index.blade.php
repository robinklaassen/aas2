@extends('master')

@section('title')
	Mijn declaraties
@endsection

@section('content')
<!-- Dit is de overzichtspagina voor je eigen declaraties -->

<!-- Titelbalk met knoppen -->
<div class="row">
	<div class="col-sm-6">
		<h1>Mijn declaraties</h1>
	</div>
	<div class="col-sm-6">
		<p class="text-right">
			@if (\Auth::user()->is_admin)
				<a class="btn btn-success" type="button" href="{{ url('declarations/admin') }}" style="margin-top:21px;">Admin dashboard</a>
			@endif
			<a class="btn btn-info" type="button" href="{{ url('declarations/files') }}" style="margin-top:21px;">Bestanden beheren</a>
			<a class="btn btn-primary" type="button" href="{{ url('declarations/upload') }}" style="margin-top:21px;">Nieuwe declaratie</a>
		</p>
	</div>
</div>

<div class="alert alert-danger alert-important">
	Let op: in ontwikkeling! Declaraties die je hier doet, worden niet serieus door de penningmeester verwerkt.
</div>

@if (\Auth::user()->profile->iban == null)
	<div class="alert alert-danger alert-important">
		Als je daadwerkelijk geld terug wil krijgen van onze penningmeester, moet je eerst een rekeningnummer invullen op je profiel!
	</div>
@endif

<hr/>

<p class="text-right">
	Totaal openstaand bedrag: <strong>{{ formatPrice($total_open) }}</strong>
</p>

<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#declarations_open" aria-controls="declarations_open" role="tab" data-toggle="tab">Openstaand</a></li>
	<li role="presentation"><a href="#declarations_closed" aria-controls="declarations_closed" role="tab" data-toggle="tab">Afgesloten</a></li>
</ul>

<div class="tab-content" style="margin-top:20px;">
	<div role="tabpanel" class="tab-pane active" id="declarations_open">
		<!-- Declaratietabel -->
		<table class="table table-hover" id="declarationsOpenTable" data-page-length="25">
			<thead>
				<tr>
					<th>Datum</th>
					<th>Bestand</th>
					<th>Omschrijving</th>
					<th>Bedrag</th>
					<th>Gift</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			
			<tbody>
				@foreach ($member->declarations()->open()->get() as $declaration)
					<tr>
						<td>{{ $declaration->date->format('Y-m-d') }}</td>
						<td>
							@unless ($declaration->filename === null || $declaration->filename == '')
								<a href="{{ asset('uploads/declarations/' . $member->id . '/' . $declaration->filename) }}" target="_blank">{{$declaration->filename}}</a>
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
	
	<div role="tabpanel" class="tab-pane" id="declarations_closed">
		<!-- Declaratietabel -->
		<table class="table table-hover" id="declarationsClosedTable" data-page-length="25">
			<thead>
				<tr>
					<th>Datum</th>
					<th>Bestand</th>
					<th>Omschrijving</th>
					<th>Bedrag</th>
					<th>Gift</th>
					<th>Afgehandeld op</th>
				</tr>
			</thead>
			
			<tbody>
				@foreach ($member->declarations()->closed()->get() as $declaration)
					<tr>
						<td>{{ $declaration->date->format('Y-m-d') }}</td>
						<td>
							@unless ($declaration->filename === null)
								<a href="{{ asset('uploads/declarations/' . $member->id . '/' . $declaration->filename) }}" target="_blank">{{$declaration->filename}}</a>
							@else
								-
							@endunless
						</td>
						<td>{{ $declaration->description }}</td>
						<td>{{ formatPrice($declaration->amount) }}</td>
						<td>{{ ($declaration->gift ? 'Ja' : 'Nee') }}</td>
						<td>{{ $declaration->closed_at->format('Y-m-d') }}</td>
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
			{'orderable':false},
			null,
			null,
			null,
			null
		]
	});
});
</script>
@endsection