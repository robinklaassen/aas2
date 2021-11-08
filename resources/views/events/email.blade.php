@extends('master')

@section('title')
	Emailadressen {{$event->naam}}
@endsection

@section('content')

<h1>Emailadressen {{$event->naam}}</h1>

<hr/>

<h2>Leiding ({{ $emails['members']->count() }})</h2>

<div class="list">
	<div class="copy-btn">
		<button class="btn" onclick="copyToClipboard(this, '#list-members')">Copy</button>
	</div>
	<pre id="list-members">{{ implode(", ", $emails["members"]->toArray()) }}</pre>
</div>

<hr />
<h2>Ongeplaatst</h2>

	<h4>Deelnemers</h4>
	<div class="list">
		<div class="copy-btn">
			<button class="btn" onclick="copyToClipboard(this, '#list-unplaced-participants')">Copy</button>
		</div>
		<pre id="list-unplaced-participants">{{ implode(", ", $emails['participants']['unplaced']['participants']->toArray()) }}</pre>
	</div>

	<h4>Ouders</h4>
	<div class="list">
		<div class="copy-btn">
			<button class="btn" onclick="copyToClipboard(this, '#list-unplaced-parents')">Copy</button>
		</div>
		<pre id="list-unplaced-parents">{{ implode(", ", $emails['participants']['unplaced']['parents']->toArray()) }}</pre>
	</div>

<h2>Geplaatst</h2>

	<h4>Deelnemers</h4>
	<div class="list">
		<div class="copy-btn">
			<button class="btn" onclick="copyToClipboard(this, '#list-placed-participants')">Copy</button>
		</div>
		<pre id="list-placed-participants">{{ implode(", ", $emails['participants']['placed']['participants']->toArray()) }}</pre>
	</div>

	<h4>Ouders</h4>
	<div class="list">
		<div class="copy-btn">
			<button class="btn" onclick="copyToClipboard(this, '#list-placed-parents')">Copy</button>
		</div>
		<pre id="list-placed-parents">{{ implode(", ", $emails['participants']['placed']['parents']->toArray()) }}</pre>
	</div>

<hr />
@foreach((\App\Models\Participant::class)::INFORMATION_CHANNEL_DESCRIPTION_TABLE as $key => $value)
	<h2>{{$value}}</h2>

	<h4>Deelnemers ({{ $emails['participants'][$key]['participants']->count() }})</h4>
	<div class="list">
		<div class="copy-btn">
			<button class="btn" onclick="copyToClipboard(this, '#list-{{$key}}-participants')">Copy</button>
		</div>
		<pre id="list-{{$key}}-participants">{{
			implode(", ", $emails['participants'][$key]['participants']->toArray())
		}}</pre>
	</div>

	<h4>Ouders ({{ $emails['participants'][$key]['parents']->count() }})</h4>
	<div class="list">
		<div class="copy-btn">
			<button class="btn" onclick="copyToClipboard(this, '#list-{{$key}}-parents')">Copy</button>
		</div>
		<pre id="list-{{$key}}-parents">{{
			implode(", ", $emails['participants'][$key]['parents']->toArray())
		}}</pre>
	</div>


@endforeach


@endsection
