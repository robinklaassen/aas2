@extends('master')

@section('title')
	Ledenkaart
@endsection


@section('content')

	<h1>Ledenkaart</h1>

	<hr/>

	<p>Legenda: donkergroen = normaal lid, lichtgroen = aspirant lid, roze = infolid.</p>

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

	const normalIcon = getColoredIcon('#1c5128');  // donkergroen
	const aspiringIcon = getColoredIcon('#4db848');  // lichtgroen
	const infoIcon = getColoredIcon('#b12e62');  // roze

	// Setup map
	var map = L.map('mapdiv', {
		minZoom: 0,
		maxZoom: 13,
	}).setView([51.505, 5.4], 7);

	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);

	const markers = @json($markers);

	const markerTypeMap = {
		'normaal': normalIcon,
		'aspirant': aspiringIcon,
		'info': infoIcon
	};

	markers.map(function (item) {
		L.marker(item.latlng, {
			icon: markerTypeMap[item.type],
		})
		.addTo(map)
		.bindTooltip(item.name)
		.bindPopup(item.link);
	});

	</script>

@endsection
