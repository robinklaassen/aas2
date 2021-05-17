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
	Er is ook automatisch een account voor je aangemaakt. Hiermee kun je inloggen op ons <a href="http://aas2.anderwijs.nl">administratiesysteem</a> om je gegevens te beheren. De details staan in de bevestigingsmail.
</p>

<p>
	<a href="http://www.anderwijs.nl">Terug naar de website van Anderwijs</a>
</p>

@endsection

@section('script')

	@if ( env('GOOGLE_SITE_TAG') !== null && env('GOOGLE_CONVERSION_NEW_MEMBER') !== null)
	<script>
		gtag('event', 'conversion', {'send_to': '{{ env("GOOGLE_SITE_TAG") }}/{{ env("GOOGLE_CONVERSION_NEW_MEMBER") }}'});
	</script>
	@endif

@endsection