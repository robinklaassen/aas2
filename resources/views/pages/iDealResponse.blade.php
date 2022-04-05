@extends('master')

@section('title')
	iDeal betaling
@endsection

@section('content')

<h1>iDeal betaling</h1>

<hr/>

@if ($status == 'paid')

<p>De betaling is succesvol ontvangen!</p>

@elseif ($status == 'cancelled')

<p>Je hebt de betaling afgebroken. Awww...</p>

@else

<p>De betaling staat nog steeds open. Wat moeten we daar nou mee?</p>

@endif

<p><a href="{{ url('iDeal') }}">Klik hier om de test te herhalen.</a></p>

@endsection
