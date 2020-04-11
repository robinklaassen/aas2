@extends('master')

@section('title')
	Emailadressen {{$event->naam}}
@endsection

@section('content')

<h1>Emailadressen {{$event->naam}}</h1>

<hr/>

<h4>Leiding ({{ $emails['members']->count() }})</h4>

<div class="list">
	<div class="copy-btn">
		<button class="btn" onclick="copyToClipboard(this, '#list-members')">Copy</button>
	</div>
	<pre id="list-members">{{ implode(", ", $emails["members"]->toArray()) }}</pre>
</div>

<hr />
@foreach((\App\Participant::class)::INFORMATION_CHANNEL_DESCRIPTION_TABLE as $key => $value)
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