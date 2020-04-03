@extends('master')

@section('title')
Pakketten
@endsection

@section('content')
<!-- Dit is de overzichtspagina voor het puntensysteem -->

<!-- Titelbalk met knoppen -->
<div class="row">
	<div class="col-sm-6">
		<h1>Pakketten</h1>
	</div>
	<div class="col-sm-6">
		<p class="text-right">
			@can("create", \App\EventPackage::class)
			<a class="btn btn-primary" type="button" href="{{ url('event-packages/create') }}" style="margin-top:21px;">
				Nieuw Pakket
			</a>
			@endcan
		</p>
	</div>
</div>

<hr />

<ul class="nav nav-tabs" role="tablist">
	@foreach($eventPackagesGrouped as $key => $value)
		<li role="presentation"><a href="#{{$key}}" aria-controls="{{$key}}" role="tab" data-toggle="tab">{{(\App\EventPackage::class)::TYPE_DESCRIPTIONS[$key]}}</a></li>
	@endforeach
</ul>

<div class="tab-content" style="margin-top:20px;">

	@foreach($eventPackagesGrouped as $key => $eventPackages_per_key)
	<div role="tabpanel" class="tab-pane" id="{{$key}}">
		<div class="row">
			<div class="col-sm-9">
				<!-- Actiestabel -->
				<table class="table table-hover" data-page-length="25">
					<thead>
						<tr>
							<th>Code</a></th>
							<th>Titel</th>
							<th>Prijs</th>
							<th></th>
							<th></th>
						</tr>
					</thead>

					<tbody>
						@foreach ($eventPackages_per_key as $eventPackage)
						<tr>
							<td><a href="{{ url('/event-packages', $eventPackage->id) }}">{{ $eventPackage->code }}</td>
							<td>{{ $eventPackage->title }}</td>
							<td>{{ $eventPackage->price }}</td>
							<td>
								@can("update", $eventPackage)
								<a href="{{ url('/event-packages', [$eventPackage->id, 'edit']) }}">
								<span class="glyphicon glyphicon-edit" data-toggle="tooltip"
									title="Bewerken"></span>
								</a>
								@endcan
							</td>
							<td>
								@can("delete", $eventPackage)
								<a href="{{ url('/event-packages', [$eventPackage->id, 'delete']) }}">
								<span class="glyphicon glyphicon-remove" data-toggle="tooltip"
									title="Verwijderen"></span>
								</a>
								@endcan
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>

	@endforeach

@endsection
