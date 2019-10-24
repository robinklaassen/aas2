@extends('master')

@section('title')
{{ $location->naam }}
@endsection


@section('content')
<!-- Dit is de pagina met gegevens voor een specifieke locatie -->

<!-- Volledige naam en knoppen -->
<div class="row">
	<div class="col-sm-6">
		<h1>{{ $location->naam }}</h1>
	</div>
	<div class="col-sm-6">
		<p class="text-right">
			@can("update", $location)
			<a class="btn btn-primary" type="button" href="{{ url('/locations', [$location->id, 'edit']) }}" style="margin-top:21px;">Bewerken</a>
			@endcan
			@can("delete", $location)
			<a class="btn btn-danger" type="button" href="{{ url('/locations', [$location->id, 'delete']) }}" style="margin-top:21px;">Verwijderen</a>
			@endcan
		</p>
	</div>
</div>

<hr />

<div id="map-canvas" style="width:100%; height:300px; margin-bottom:10px;"></div>

<div class="row">

	<!-- Kampgegevens -->
	<div class="col-md-6">
		<table class="table table-hover">
			<caption>Locatiegegevens</caption>
			<tr>
				<td>Adres</td>
				<td>{{ $location->adres }}</td>
			</tr>
			<tr>
				<td>Postcode</td>
				<td>{{ $location->postcode }}</td>
			</tr>
			<tr>
				<td>Plaats</td>
				<td>{{ $location->plaats }}</td>
			</tr>
			@can("viewAdvanced", $location)
			<tr>
				<td>Beheerder</td>
				<td>{{ $location->beheerder }}</td>
			</tr>
			@endcan
			<tr>
				<td>Website</td>
				<td><a href="{{ $location->website }}">{{ $location->website }}</a></td>
			</tr>
			<tr>
				<td>Telefoon</td>
				<td>{{ $location->telefoon }}</td>
			</tr>
			@can("viewAdvanced", $location)
			<tr>
				<td>Emailadres</td>
				<td><a href="mailto:{{ $location->email }}">{{ $location->email }}</a></td>
			</tr>
			<tr>
				<td>Prijsinformatie</td>
				<td style="white-space:pre-wrap;">{!! $location->prijsinfo !!}</td>
			</tr>
			@endcan

		</table>
	</div>

	<div class="col-md-6">
		<table class="table table-hover">
			<caption>Kampen ({{$location->events()->where('type','kamp')->count()}})</caption>
			@forelse ($location->events()->where('type','kamp')->orderBy('datum_start','desc')->get() as $event)
			<tr>
				<td><a href="{{ url('/events', $event->id) }}">{{ $event->naam }}</a></td>
				<td>
					@if ($event->reviews->count() > 0)
					<a href="{{ url('/locations', [$location->id, 'reviews', $event->id]) }}">
						<span class="glyphicon glyphicon-dashboard" aria-hidden="true" data-toggle="tooltip" title="Enquetes bekijken"></span>
					</a>
					@endif
				</td>
				<td>{{ $event->code }}</td>
			</tr>
			@empty
			<tr>
				<td>Geen kampen gevonden</td>
			</tr>
			@endforelse
		</table>

		<table class="table table-hover">
			<caption>Trainingen ({{$location->events()->where('type','training')->count()}})</caption>
			@forelse ($location->events()->where('type','training')->orderBy('datum_start','desc')->get() as $event)
			<tr>
				<td><a href="{{ url('/events', $event->id) }}">{{ $event->naam }}</a></td>
				<td>{{ $event->code }}</td>
			</tr>
			@empty
			<tr>
				<td>Geen trainingen gevonden</td>
			</tr>
			@endforelse
		</table>

		<table class="table table-hover">
			<caption>Overige evenementen ({{$location->events()->where('type','overig')->count()}})</caption>
			@forelse ($location->events()->where('type','overig')->orderBy('datum_start','desc')->get() as $event)
			<tr>
				<td><a href="{{ url('/events', $event->id) }}">{{ $event->naam }}</a></td>
				<td>{{ $event->code }}</td>
			</tr>
			@empty
			<tr>
				<td>Geen overige evenementen gevonden</td>
			</tr>
			@endforelse
		</table>
	</div>

</div>

@can("viewAdvanced", $location)
	@include('partials.comments', [ 'comments' => $location->comments, 'type' => 'App\Location', 'key' => $location->id ])
@endif

@endsection

@section('header')
<!-- Load Google maps script -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

<!-- Initialize map -->
<script type="text/javascript">
	function initialize() {
		// First initialize map
		var geocoder = new google.maps.Geocoder();
		var mapOptions = {
			center: {
				lat: 52.31,
				lng: 5.55
			},
			zoom: 8
		};
		var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

		// Then code address and find
		var address = '<?php echo $locString; ?>';
		geocoder.geocode({
			'address': address
		}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				map.setCenter(results[0].geometry.location);
				var marker = new google.maps.Marker({
					map: map,
					position: results[0].geometry.location
				});
			} else {
				alert("Geocode was not successful for the following reason: " + status);
			}
		});
	}
	google.maps.event.addDomListener(window, 'load', initialize);
</script>
@endsection