@extends('master')

@section('title')
Kampvisualisatie
@endsection


@section('content')

<h1>Kampvisualisatie</h1>

<hr />

{{-- TODO move styling out of inline --}}
<div id="mapdiv" style="width:100%; height:750px; margin-bottom:10px;"></div>

@endsection

@section('script')
<script type="text/javascript">

    let camps = {{ \Illuminate\Support\Js::from($camps) }};

    console.log(camps);

    let map = L.map('mapdiv').setView([51.505, 5.4], 7);

	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);

</script>
@endsection