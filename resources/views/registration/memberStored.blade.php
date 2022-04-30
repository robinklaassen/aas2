@extends('master')

@section('title')
	Aangemeld als vrijwilliger
@endsection

@section('content')

<h1>Bedankt voor je aanmelding!</h1>

<hr/>

<p>
	Je hebt je succesvol aangemeld voor een Anderwijskamp. Je ontvangt een automatische bevestigingsmail op het opgegeven emailadres. Verder neemt iemand van Anderwijs binnenkort contact met je op om het verdere proces uit te leggen en eventuele vragen te beantwoorden.
</p>

<p>
	Er is ook automatisch een account voor je aangemaakt. Hiermee kun je inloggen op ons <a href="{{ url('/') }}">administratiesysteem</a> om je gegevens te beheren. De details staan in de bevestigingsmail.
</p>

<p>
	<a href="https://www.anderwijs.nl">Terug naar de website van Anderwijs</a>
</p>

@endsection

@section('script')

	@if ( config('google.site_tag') !== null && config('google.conversion_new_member') !== null)
	<script>
		gtag('event', 'conversion', {'send_to': '{{ config("google.site_tag") }}/{{ config("google.conversion_new_member") }}'});
	</script>
	@endif

@endsection