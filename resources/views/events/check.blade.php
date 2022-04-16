@extends ('master')

@section ('title')
	Vakdekking {{$event->naam}}
@endsection

@section ('content')
<h1>Vakdekking {{ $event->naam }} ({{ $event->code }})</h1>
<h4>
	{{ ($onlyPlaced) ? 'Alleen geplaatste deelnemers' : 'Alle deelnemers' }}. 
	<a href="{{ url('/events', [$event->id, 'check', $onlyPlaced ? 'all' : 'placed']) }}">
		Toon {{ $onlyPlaced ? 'alle deelnemers' : 'alleen geplaatste deelnemers' }}.
	</a>
</h4>

<hr/>

<div class="row">
<div class="col-md-8">
<table class="table table-hover">
	<thead>
		<tr>
			<th>Vak</th>
			<th>Deelnemers</th>
			<th>Leiding</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($coverageInfo as $c)
			<tr @class([$c['rowClass'] => $c['participants']->count() > 0])>
				<td>
					{{ $c['naam'] }}
				</td>

				<td>
					@foreach ($c['participants'] as $p)
						{{ $p->volnaam }} ({{ $p->klas }})<br/>
					@endforeach
				</td>

				<td>
					@foreach ($c['members'] as $m)
						{{ $m->volnaam }} ({{ $m->pivot->klas }})<br/>
					@endforeach
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
</div>
</div>
@endsection