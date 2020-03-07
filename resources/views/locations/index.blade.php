@extends('master')

@section('title')
	Locaties
@endsection


@section('content')
<!-- Dit is de overzichtspagina voor lokaties -->

<!-- Titelbalk met knoppen -->
<div class="row">
	<div class="col-sm-6">
		<h1>Locaties</h1>
	</div>
	<div class="col-sm-6">
		<p class="text-right">
			@can("create", \App\Location::class)
			<a class="btn btn-primary" type="button" href="{{ url('locations/create') }}" style="margin-top:21px;">Nieuwe locatie</a>
			@endcan
		</p>
	</div>
</div>

<hr/>

<!-- Locatietabel -->
<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Naam</th>
				<th>Plaats</th>
				@can("viewAdvancedAny", \App\Location::class)
				<th>Telefoon</th>
				<th>Email</th>
				@endcan
				<th><span data-toggle="tooltip" title="Kampen">K</span></th>
				<th><span data-toggle="tooltip" title="Trainingen">T</span></th>
				<th><span data-toggle="tooltip" title="Overige evenementen">O</span></th>
			</tr>
		</thead>
		
		<tbody>
			@foreach ($locations as $location)
				@if ($location->events()->count() != 0)
					<tr>
						<td><a href="{{ url('/locations', $location->id) }}">{{ $location->naam }}</a></td>
						<td>{{ $location->plaats }}</td>
						@can("viewAdvancedAny", \App\Location::class)
						<td>{{ $location->telefoon }}</td>
						<td><a href="mailto:{{ $location->email }}">{{ $location->email }}</a></td>
						@endcan
						<td>{{ $location->events()->where('type','kamp')->count() }}</td>
						<td>{{ $location->events()->where('type','training')->count() }}</td>
						<td>{{ $location->events()->where('type','overig')->count() }}</td>
					</tr>
				@endif
			@endforeach
			<!-- Divider row -->
			<tr>
				<td style="padding-top:25px;"><strong>Ongebruikte locaties</strong></td>
				<td></td><td></td><td></td><td></td><td></td><td></td>
			</tr>
			@foreach ($locations as $location)
				@if ($location->events()->count() == 0)
					<tr>
						<td><a href="{{ url('/locations', $location->id) }}">{{ $location->naam }}</a></td>
						<td>{{ $location->plaats }}</td>
						<td>{{ $location->telefoon }}</td>
						<td><a href="mailto:{{ $location->email }}">{{ $location->email }}</a></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				@endif
			@endforeach
		</tbody>
	</table>
</div>
@endsection