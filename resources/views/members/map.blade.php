@extends('master')

@section('title')
	Ledenkaart
@endsection


@section('content')

	<h1>Ledenkaart</h1>

	<hr/>

	<p>Legenda: rood = normaal, groen = aspirant, blauw = info.</p>

	<div id="mapdiv" style="width:100%; height:750px; margin-bottom:10px;"></div>

@endsection

@section('script')

	<script type="text/javascript">

	function getColoredIcon(color) {
		const markerHtmlStyles = `
			background-color: ${color};
			width: 3rem;
			height: 3rem;
			display: block;
			left: -1.5rem;
			top: -1.5rem;
			position: relative;
			border-radius: 3rem 3rem 0;
			transform: rotate(45deg);
			border: 1px solid #FFFFFF`

		return L.divIcon({
			// className: "my-custom-pin",
			iconAnchor: [0, 24],
			labelAnchor: [-6, 0],
			popupAnchor: [0, -36],
			html: `<span style="${markerHtmlStyles}" />`
		});
	}

	const redIcon = getColoredIcon('red');
	const greenIcon = getColoredIcon('green');
	const blueIcon = getColoredIcon('blue');

	// Setup map
	var map = L.map('mapdiv').setView([51.505, 5.4], 7);

	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);

	const markers = @json($markers);

	const markerTypeMap = {
		'normaal': redIcon,
		'aspirant': greenIcon,
		'info': blueIcon
	};

	markers.map(function (item) {
		L.marker(item.latlng, {
			icon: markerTypeMap[item.type],
		})
		.addTo(map)
		.bindPopup(item.name);
	});

	</script>

@endsection
