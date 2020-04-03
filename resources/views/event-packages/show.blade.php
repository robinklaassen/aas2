@extends('master')

@section('title')
Pakket {{ $eventPackage->code }}
@endsection


@section('content')
<!-- Dit is de pagina met gegevens voor een specifieke locatie -->

<!-- Volledige naam en knoppen -->
<div class="row">
	<div class="col-sm-6">
		<h1>Pakket {{ $eventPackage->code }}</h1>
	</div>
	<div class="col-sm-6">
		<p class="text-right">
			@can("update", $eventPackage)
			<a class="btn btn-primary" type="button" href="{{ url('/event-packages', [$eventPackage->id, 'edit']) }}" style="margin-top:21px;">Bewerken</a>
			@endcan
			@can("delete", $eventPackage)
			<a class="btn btn-danger" type="button" href="{{ url('/event-packages', [$eventPackage->id, 'delete']) }}" style="margin-top:21px;">Verwijderen</a>
			@endcan
		</p>
	</div>
</div>

<hr />

<div class="row">
	<!-- Kampgegevens -->
	<div class="col-md-12">
		<table class="table table-hover">
			<tr>
				<td>Code</td>
				<td>{{ $eventPackage->code }}</td>
			</tr>
			<tr>
				<td>Titel</td>
				<td>{{ $eventPackage->title }}</td>
            </tr>
            <tr>
				<td>Type</td>
				<td>{{ (\App\EventPackage::class)::TYPE_DESCRIPTIONS[$eventPackage->type] }}</td>
			</tr>
			<tr>
				<td>Prijs</td>
				<td>&euro; {{ $eventPackage->price }}</td>
			</tr>
			<tr>
				<td>Omschrijving</td>
				<td>{{ $eventPackage->description }}</a></td>
			</tr>
		</table>
	</div>
</div>


@endsection