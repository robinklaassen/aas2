@extends('master')

@section('title')
	Budget {{$event->naam}}
@endsection

@section('header')
<style>
	.txt-r {
		text-align: right;
	}
</style>
@endsection

@section('content')


<h1>Budget {{$event->naam}}</h1>

<hr/>

<div class="row">
	<div class="col-md-6">

		<p>
			<strong>Let op!</strong> Deze tool rekent op dit moment alleen met <b>hele</b> kampdagen, terwijl de begroting ook halve dagen kent. In de toekomst komt een nieuwe versie die beter aansluit op de werkelijkheid.
		</p>

		<table class="table">
			<thead>
				<tr>
					<th></th>
					<th>Aantal</th>
					<th>Dagen</th>
					<th>Budget pppd</th>
					<th class="txt-r">Totaal</th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<th>Leiding (vol)</th>
					<td>{{$data->num_m}}</td>
					<td>{{$data->days_m}}</td>
					<td>€ {{$data->pppd}}</td>
					<td class="txt-r">€ {{$data->num_m * $data->days_m * $data->pppd}}</td>
				</tr>
				@foreach ($wissel as $member)
				<tr>
					<td>{{$member['name']}}</td>
					<td></td>
					<td>{{$member['days']}}</td>
					<td>€ {{$data->pppd}}</td>
					<td class="txt-r">€ {{$member['days'] * $data->pppd}}</td>
				</tr>
				@endforeach
				<tr>
					<th>Deelnemers</th>
					<td>{{$data->num_p}}</td>
					<td>{{$data->days_p}}</td>
					<td>€ {{$data->pppd}}</td>
					<td class="txt-r">€ {{$data->num_p * $data->days_p * $data->pppd}}</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="txt-r"><strong>€ {{$data->total}}</strong></td>
				</tr>
			</tbody>
		</table>

	</div>
</div>

@endsection