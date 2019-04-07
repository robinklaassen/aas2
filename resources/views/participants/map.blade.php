@extends('master')

@section('title')
	Deelnemerkaart
@endsection


@section('content')

<h1>Deelnemerkaart</h1>

<hr/>

<p><b>Noot</b>: de markers worden een voor een geladen omdat Google een limiet stelt aan het aantal queries per seconde. Je moet dus even geduld hebben.</p>

<p>
{{ $amount }} deelnemers worden getoond vanuit de volgende kampen:
<ul>
	@foreach ($campnames as $name)
	<li>{{$name}}</li>
	@endforeach
</ul>
</p>

<p>Legenda: rood = 1 keer, groen = 2 keer, blauw = 3+ keer meegeweest.</p>

<div id="map-canvas" style="width:100%; height:1000px; margin-bottom:10px;"></div>

@endsection


@section('header')
<!-- Load Google maps script -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

<script type="text/javascript">
function initialize() {
	// First initialize map
	var geocoder = new google.maps.Geocoder();
	var mapOptions = {
		center: { lat: 52.31, lng: 5.55},
		zoom: 8
	};
	var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	
	// Then code addresses and place markers
	var participantData = <?php echo $participantJSON; ?>;
	var timer = 0;
	participantData.forEach(function(p) {
		window.setTimeout(function() {
			geocoder.geocode( { 'address': p.address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					var marker = new google.maps.Marker({
						map: map,
						position: results[0].geometry.location,
						icon: p.markerURL,
						title: p.name,
						animation: google.maps.Animation.DROP
					});
				} else {
					alert("Geocode was not successful for the following reason: " + status);
				}
			});
		}, timer += 1000);
	});
}

// Only load the map when the DOM is fully loaded
google.maps.event.addDomListener(window, 'load', initialize);
</script>
@endsection