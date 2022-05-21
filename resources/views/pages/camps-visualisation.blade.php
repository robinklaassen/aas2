@extends('master')

@section('title')
Kampvisualisatie
@endsection


@section('header')
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
@endsection


@section('content')

<h1>Kampvisualisatie</h1>

<hr />

{{-- TODO move styling out of inline --}}
<div id="mapdiv" style="width:100%; height:900px; margin-bottom:10px;"></div>

@endsection

@section('script')
<script type="text/javascript">

    let camps = {{ \Illuminate\Support\Js::from($camps) }};

    // console.log(camps);

    let map = L.map('mapdiv', {
        zoom: 8,
        center: [52.1, 5.4],
        // timeDimension: true,
        // timeDimensionOptions: {
        //     timeInterval: "2008-01-01/" + new Date(),
        //     period: "P1Y",
        // },
        // timeDimensionControl: true,
        // timeDimensionControlOptions: {
        //     loopButton: true,
        //     autoPlay: true,
        // },
    });

    var Stamen_TerrainBackground = L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/terrain-background/{z}/{x}/{y}{r}.{ext}', {
        attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        subdomains: 'abcd',
        minZoom: 0,
        maxZoom: 18,
        ext: 'png'
    });

    Stamen_TerrainBackground.addTo(map);

	// L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	// 	attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	// }).addTo(map);

</script>
@endsection