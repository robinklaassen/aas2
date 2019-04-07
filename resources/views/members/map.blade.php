@extends('master')

@section('title')
	Ledenkaart
@endsection


@section('content')

<h1>Ledenkaart</h1>

<hr/>

<p><b>Noot</b>: de markers worden een voor een geladen omdat Google een limiet stelt aan het aantal queries per seconde. Je moet dus even geduld hebben.</p>

<p>Legenda: rood = normaal, groen = aspirant, blauw = info.</p>

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
	var memberData = <?php echo $memberJSON; ?>;
	var timer = 0;
	memberData.forEach(function(member) {
		window.setTimeout(function() {
			geocoder.geocode( { 'address': member.address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					var marker = new google.maps.Marker({
						map: map,
						position: results[0].geometry.location,
						icon: member.markerURL,
						title: member.name,
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