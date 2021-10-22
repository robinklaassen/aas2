@extends ('master')

@section ('title')
	Vakdekking {{$event->naam}}
@endsection

@section ('content')
<h1>Vakdekking {{ $event->naam }} ({{ $event->code }})</h1>
<h4>{{ ($type == 'all') ? 'Alle deelnemers' : 'Alleen geplaatste deelnemers' }} (<a href="{{ url('/events', [$event->id, 'check']) }}/{{ ($type == 'all') ? 'placed' : 'all' }}">wisselen</a>)</h4>
<h4 class="changeable">Alleen gevraagde vakken (<a href="#" onclick="changeView()">wisselen</a>)</h4>
<h4 class="changeable" style="display:none;">Alle vakken (<a href="#" onclick="changeView()">wisselen</a>)</h4>

<hr/>

<p class="small">Beweeg je muis over de getallen om namen en niveaus te zien, en over de icoontjes om de statusberichten te zien.</p>

<div class="row">
<div class="col-md-8">
<table class="table table-hover">
	<thead>
		<tr>
			<th>Vak</th>
			<th>Deelnemers</th>
			<th>Leiding</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($coverageInfo as $c)
			<tr @if ($c['participants']->isEmpty()) class="changeable" style="display:none;" @endif >
				<td>{{ $c['naam'] }}</td>

				<td>
					<span data-toggle="tooltip" data-html="true" data-placement="right"
					 title="@foreach ($c['participants'] as $p)
					 	{{ $p->voornaam }} ({{ $p->klas }})<br/>
					 @endforeach">
					 	{{ $c['participants']->count() }}
					</span>
				</td>

				<td>
					<span 
					data-toggle="tooltip" data-html="true" data-placement="right" 
					title="@foreach ($c['members'] as $m)
						{{ $m->voornaam }} ({{ $m->pivot->klas }})<br/>
					@endforeach">
						{{ $c['members']->count() }}
					</span>
				</td>

				<td>
					@if ($c['status'] == 'ok')
						<span data-toggle="tooltip" data-placement="right" title="OK!" class="glyphicon glyphicon-ok"></span>
					@elseif ($c['status'] == 'badquota')
						<span data-toggle="tooltip" data-placement="right" title="Niet genoeg leiding!" class="glyphicon glyphicon-alert"></span>
					@elseif ($c['status'] == 'badlevel')
						<span data-toggle="tooltip" data-placement="right" title="Onvoldoende niveau!" class="glyphicon glyphicon-alert"></span>
					@endif
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
</div>
</div>
@endsection

@section ('footer')
<script type="text/javascript">
($(document).ready(function(){
	changeView = function() {
		$(".changeable").toggle();
	}
}));
</script>
@endsection