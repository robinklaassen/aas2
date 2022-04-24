@extends('master')

@section('title')
Inschrijven vrijwilliger
@endsection

@section('header')
<style>
	a {
		color: #51B848;
	}

	a:hover {
		color: #1D5027;
	}

	[type=submit].btn {
		background-color: #1D5027;
		border: 0;
	}

	[type=submit].btn:hover {
		background-color: #51B848;
	}

	[type=submit].btn:active {
		background-color: #51B848;
	}
</style>
@endsection

@section('content')
<!-- Dit is het formulier voor het inschrijven van een nieuw lid -->

<h1><img src="{{ url('images/anderwijs-logo.png') }}" alt="Logo Anderwijs" style="height:2.5em;margin-right:30px;vertical-align:bottom;"> <span style="white-space:nowrap;">Inschrijven als nieuwe vrijwilliger</span></h1>

<hr />

<p>Wat leuk dat je met ons op kamp wil gaan. Vul het onderstaande formulier zo volledig mogelijk in en wij nemen zo spoedig mogelijk contact met je op over het verdere proces. De informatie die je opgeeft wordt alleen door Anderwijs verwerkt en bewaard, en zal <b>nooit</b> worden gedeeld met derden.</p>

<div class="well"><strong>Let op! </strong>Ben je al eens eerder met ons op kamp geweest? Meld je dan niet hier opnieuw aan, maar log in op <a href="http://aas2.anderwijs.nl">AAS 2.0</a> of stuur een mailtje naar de <a href="mailto:kamp@anderwijs.nl">kampcommissie</a>.</div>

@include ('errors.list')

{!! Form::open(['url' => 'register-member']) !!}

<h3>Persoons- en contactgegevens</h3>

<div class="row">
	<div class="col-sm-5 form-group">
		{!! Form::label('voornaam', 'Voornaam:') !!}
		{!! Form::text('voornaam', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::label('tussenvoegsel', 'Tussenvoegsel:') !!}
		{!! Form::text('tussenvoegsel', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-5 form-group">
		{!! Form::label('achternaam', 'Achternaam:') !!}
		{!! Form::text('achternaam', null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-5 form-group">
		{!! Form::label('geboortedatum', 'Geboortedatum:') !!}
		{!! Form::input('date', 'geboortedatum', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-7 form-group">
		{!! Form::label('geslacht', 'Geslacht:') !!}<br />
		<div style="margin-top:10px;">
			{!! Form::radio('geslacht', 'M', 0) !!} Man
			{!! Form::radio('geslacht', 'V', 0, ['style' => 'margin-left:20px;']) !!} Vrouw
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-5 form-group">
		{!! Form::label('adres', 'Adres:') !!}
		{!! Form::text('adres', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::label('postcode', 'Postcode:') !!}
		{!! Form::text('postcode', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-5 form-group">
		{!! Form::label('plaats', 'Woonplaats:') !!}
		{!! Form::text('plaats', null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-5 form-group">
		{!! Form::label('telefoon', 'Telefoonnummer:') !!}
		{!! Form::text('telefoon', null, ['class' => 'form-control', 'maxlength' => 10, 'placeholder' => '10 cijfers']) !!}
	</div>

	<div class="col-sm-7 form-group">
		{!! Form::label('email', 'Emailadres:') !!}
		{!! Form::email('email', null, ['class' => 'form-control']) !!}
	</div>
</div>

<h3>Kamp selecteren</h3>

<div class="row">
	<div class="col-sm-5">
		<div class="well well-sm">
			Zie ook het <a href="http://www.anderwijs.nl/agenda" target="_blank">kampschema</a> (link wordt geopend in nieuw venster).
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-5 form-group">
		{!! Form::label('selected_camp', 'Kamp:') !!}
		<select class="form-control" id="selected_camp" name="selected_camp">
			@foreach($camps as $c)
				<option value="{{ $c->id }}" @if($c->vol) disabled @endif>{{ $c->full_title }}</option>
			@endforeach
		</select>
	</div>
</div>

<h3>Bijspijkerinformatie</h3>

<div class="row">
	<div class="col-sm-2 form-group">
		{!! Form::label('eindexamen', 'Niveau eindexamen:') !!}
		{!! Form::select('eindexamen', ['VMBO' => 'VMBO', 'HAVO' => 'HAVO', 'VWO' => 'VWO'], null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-5 form-group">
		{!! Form::label('studie', 'Studie:') !!}
		{!! Form::text('studie', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::label('afgestudeerd', 'Afgestudeerd?') !!}<br />
		<div style="margin-top:10px;">
			{!! Form::radio('afgestudeerd', 1, 0) !!} Ja
			{!! Form::radio('afgestudeerd', 0, 0, ['style' => 'margin-left:20px;']) !!} Nee
		</div>
	</div>
</div>

<div class="well">
	Geef hieronder aan welke vakken je zou kunnen geven op kamp. Minstens één en zoveel als je wilt. Voorzie ieder vak ook van een klas, dit slaat op het hoogste niveau waarop je het vak kunt geven, gerekend naar VWO (voor HAVO moet je dit dus compenseren).
</div>

@for ($i = 0; $i < 4; $i++) <div class="row">
	<div class="col-sm-3 form-group">
		{!! Form::select('vak'.(2*$i), $course_options, null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::input('number', 'klas'.(2*$i), null, ['class' => 'form-control', 'min' => 1, 'max' => 6, 'step' => 1]) !!}
	</div>

	<div class="col-sm-3 col-sm-offset-1 form-group">
		{!! Form::select('vak'.((2*$i)+1), $course_options, null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::input('number', 'klas'.((2*$i)+1), null, ['class' => 'form-control', 'min' => 1, 'max' => 6, 'step' => 1]) !!}
	</div>
	</div>
@endfor

<h3>Overige informatie</h3>
<div class="row">
	<div class="col-sm-12 form-group">
		<b>Hoe ben je bij Anderwijs terechtgekomen?</b><br />
		<small>Meerdere antwoorden mogelijk</small>
		@foreach ($hoebij_options as $i => $option)
		<div class="checkbox">
			<label>
				{!! Form::checkbox('hoebij[]', $option) !!} {{ $option }}
			</label>
		</div>
		@endforeach

		<div class="checkbox form-inline">
			<label>
				{!! Form::checkbox('hoebij[]', '0') !!} Anders, namelijk:
			</label>

			{!! Form::text('hoebij_anders', null, ['class' => ['form-control', 'input-sm']]) !!}
		</div>

	</div>
</div>

<div class="form-group">
	{!! Form::label('opmerkingen', 'Overige informatie:') !!}
	{!! Form::textarea('opmerkingen', null, ['class' => 'form-control', 'placeholder' => 'Denk bijvoorbeeld aan speciale diëten, allergieën, medicijnen of andere dingen waar rekening mee gehouden moet worden op kamp.' ]) !!}
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="checkbox">
			<label>
				{!! Form::checkbox('vog', 1) !!} Ik ga akkoord dat ik een <strong>Verklaring Omtrent het Gedrag (VOG)</strong> moet aanvragen en inleveren om mee te mogen op kamp. Anderwijs vergoedt eventuele kosten die hieraan verbonden zijn (over de aanvraagprocedure volgt apart bericht).
			</label>
		</div>
		<div class="checkbox">
			<label>
				{!! Form::checkbox('privacy', 1) !!} Ik geef Anderwijs toestemming om deze gegevens te verwerken zoals beschreven in het
				<a href="http://www.anderwijs.nl/anderwijs/privacy/" target="_blank">privacystatement</a>.
			</label>
		</div>
	</div>
</div>

<br>

<div class="form-group">
	{!! Form::submit('Inschrijven', ['class' => 'btn btn-primary form-control']) !!}
</div>

{!! Form::close() !!}

@endsection