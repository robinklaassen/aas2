@extends('master')

@section('title')
Inschrijven deelnemer
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
<!-- Dit is het formulier voor het inschrijven van een nieuwe deelnemer -->

<h1><img src="https://www.anderwijs.nl/wp-content/uploads/2016/03/Test-6-2.png" alt="Logo Anderwijs"
		style="height:2.5em;margin-right:30px;vertical-align:bottom;"> <span style="white-space:nowrap;">Nieuwe
		deelnemer inschrijven</span></h1>

<hr />

<!--
<div class="alert alert-danger alert-important" role="alert">
	<b>Let op!</b> Er wordt op dit moment technisch onderhoud gepleegd aan dit formulier. Gelieve het pas weer te gebruiken wanneer deze melding weg is. Dank voor uw begrip.
</div>
-->

<p>Vul het onderstaande formulier zo volledig mogelijk in. Benieuwd hoe het na inschrijving verder gaat? Bekijk dan het
	<a href="http://www.anderwijs.nl/kampen/stappenplan-inschrijving/" target="_blank">stappenplan voor de
		inschrijving</a> (link opent in een nieuw venster). De informatie die je opgeeft wordt alleen door Anderwijs
	verwerkt en bewaard, en zal <b>nooit</b> worden gedeeld met derden.</p>

<div class="well">
	<strong>Let op: is uw kind al eerder met ons op kamp geweest?</strong> Schrijf hem/haar dan niet hier opnieuw in,
	maar log in op ons <a href="http://aas2.anderwijs.nl">administratiesysteem</a>. Daar kunt u de bij ons bekende
	gegevens controleren en aanvullen en uw kind eenvoudig op een nieuw kamp sturen. Heeft u nog geen account, of bent u
	uw inloggegevens vergeten, stuur dan een mail naar de <a href="mailto:kantoor@anderwijs.nl">kantoorcommissie</a>.
</div>

@include ('errors.list')

{!! Form::open(['url' => 'register-participant']) !!}

<h3>Persoons- en contactgegevens deelnemer</h3>

<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('voornaam', 'Voornaam:') !!}
		{!! Form::text('voornaam', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::label('tussenvoegsel', 'Tussenvoegsel:') !!}
		{!! Form::text('tussenvoegsel', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-4 form-group">
		{!! Form::label('achternaam', 'Achternaam:') !!}
		{!! Form::text('achternaam', null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('geboortedatum', 'Geboortedatum:') !!}
		{!! Form::input('date', 'geboortedatum', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-4 form-group">
		{!! Form::label('geslacht', 'Geslacht:') !!}<br />
		<div style="margin-top:10px;">
			{!! Form::radio('geslacht', 'M', 0) !!} Man
			{!! Form::radio('geslacht', 'V', 0, ['style' => 'margin-left:20px;']) !!} Vrouw
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('telefoon_deelnemer', 'Telefoonnummer:') !!}
		{!! Form::text('telefoon_deelnemer', null, ['class' => 'form-control', 'maxlength' => 10, 'placeholder' => '10
		cijfers']) !!}
	</div>

	<div class="col-sm-6 form-group">
		{!! Form::label('email_deelnemer', 'Emailadres:') !!}
		{!! Form::email('email_deelnemer', null, ['class' => 'form-control']) !!}
	</div>
</div>

<h3>Contactgegevens ouder(s)/verzorger(s)</h3>

<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('adres', 'Adres:') !!}
		{!! Form::text('adres', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::label('postcode', 'Postcode:') !!}
		{!! Form::text('postcode', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-4 form-group">
		{!! Form::label('plaats', 'Woonplaats:') !!}
		{!! Form::text('plaats', null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('telefoon_ouder_vast', 'Telefoonnummer vast:') !!}
		{!! Form::text('telefoon_ouder_vast', null, ['class' => 'form-control', 'maxlength' => 10, 'placeholder' => '10
		cijfers']) !!}
	</div>

	<div class="col-sm-4 form-group">
		{!! Form::label('telefoon_ouder_mobiel', 'Telefoonnummer mobiel:') !!}
		{!! Form::text('telefoon_ouder_mobiel', null, ['class' => 'form-control', 'maxlength' => 10, 'placeholder' =>
		'10 cijfers']) !!}
	</div>

	<div class="col-sm-4 form-group">
		{!! Form::label('email_ouder', 'Emailadres:') !!}
		{!! Form::email('email_ouder', null, ['class' => 'form-control']) !!}
	</div>
</div>

<h3>Contactvoorkeuren</h3>

<div class="row">
	<div class="col-sm-9">
		<p class="well well-sm">
			Bij inschrijving voor een kamp geeft u Anderwijs automatisch toestemming om u per mail of telefoon te mogen
			contacteren met vragen of informatie met betrekking tot dat specifieke kamp. Daarnaast bieden we u de
			mogelijkheid om per mail op de hoogte gehouden te worden van nieuwe kampen en eventuele aanbiedingen &mdash;
			wilt u die ontvangen, kiest u dan hieronder voor 'Ja'.
		</p>
	</div>
</div>

<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('mag_gemaild', 'Mailings ontvangen:') !!}
		{!! Form::select('mag_gemaild', [0 => 'Nee', 1 => 'Ja'], null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-5">
		<h3>Kamp selecteren</h3>
		<div class="well well-sm">
			Zie ook de <a href="http://www.anderwijs.nl/kampen/kampagenda/" target="_blank">kampagenda</a> (link wordt
			geopend in nieuw venster).<br /><br />
			<strong>Let op!</strong> De deelnemer moet het volledige kamp beschikbaar zijn. Later komen of eerder
			weggaan is niet toegestaan.
		</div>
		<div class="form-group">
			<select class="form-control" id="selected_camp" name="selected_camp" onchange="checkpackages()	">
				<?php foreach ($camps as $camp) { ?>
				<option value="{{$camp->id}}" {{ ($camp->vol) ? 'disabled' : '' }}>{{$camp->full_title}}</option>
				<?php } ?>
			</select>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6" style="display: none" id="selected_package_container">
			<h3>Pakket</h3>
			<div class="well">
				Bij dit kamp kunt u kiezen uit meerdere pakketten. Maak uw keuze:
			</div>
			<div class="form-group">
				<select class="form-control" id="selected_package" name="selected_package">

				</select>
			</div>
		</div>
	</div>

	<div class="col-sm-7">
		<h3>Kosten</h3>
		<div class="well well-sm">
			Klik <a href="http://www.anderwijs.nl/kampen/kampagenda/" target="_blank">hier</a> voor de actuele
			kampprijzen. Anderwijs kent een kortingsregeling op de kampprijs afhankelijk van het bruto maandelijks
			gezinsinkomen. Controleer ook de <a href="http://www.anderwijs.nl/algemene-voorwaarden/"
				target="_blank">voorwaarden</a> om voor korting in aanmerking te komen (links worden geopend in een
			nieuw venster). Als u in aanmerking komt voor korting, stuur dan ook een inkomensverklaring van u en uw
			(eventuele) partner naar onze postbus t.a.v. de penningmeester.
		</div>
		<div class="form-group">
			{!! Form::label('inkomen', 'Bruto maandinkomen:') !!}
			{!! Form::select('inkomen', ['Meer dan € 3400 (geen korting)', 'Tussen € 2200 en € 3400 (korting: 15%)',
			'Tussen € 1300 en € 2200 (korting: 30%)', 'Minder dan € 1300 (korting: 50%)'], null, ['class' =>
			'form-control']) !!}
		</div>
	</div>
</div>

<h3>Bijspijkerinformatie</h3>

<div class="row">
	<div class="col-sm-9">
		<div class="well well-sm">
			Gaat uw kind op zomerkamp? Geef dan bij klas het huidige schooljaar op, dus niet de klas waar uw kind
			naartoe gaat. Let er ook op dat uw kind zelf schoolboeken moet meenemen!
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-5 form-group">
		{!! Form::label('school', 'Naam school:') !!}
		{!! Form::text('school', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::label('niveau', 'Niveau:') !!}
		{!! Form::select('niveau', ['VMBO' => 'VMBO', 'HAVO' => 'HAVO', 'VWO' => 'VWO'], null, ['class' =>
		'form-control']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::label('klas', 'Klas:') !!}
		{!! Form::input('number', 'klas', null, ['class' => 'form-control', 'min' => 1, 'max' => 6, 'step' => 1]) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-9">
		<div class="well well-sm">
			Hieronder kunt u de vakken waar op kamp aandacht aan moet worden besteed, opgeven. Bij ieder vak kunt u in
			het tekstvak ernaast extra informatie opgeven, zoals de onderwerpen en moeilijkheden in kwestie.
		</div>
	</div>
</div>

@for ($i = 0; $i < 6; $i++) <div class="row">
	<div class="col-sm-3 form-group">
		{!! Form::select('vak'.$i, $course_options, null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-6 form-group">
		{!! Form::textarea('vakinfo'.$i, null, ['class' => 'form-control', 'rows' => 3]) !!}
	</div>
	</div>
	@endfor

	<h3>Betaalmethode</h3>
	<div class="row">
		<div class="col-sm-6 form-group">
			<div class="radio">
				<label>
					{!! Form::radio('iDeal', 1, true) !!}
					<b>Direct betalen met iDeal</b>
				</label>
			</div>
			<div class="radio">
				<label>
					{!! Form::radio('iDeal', 2) !!}
					Per bankoverschrijving (instructies in de bevestigingsmail)
				</label>
			</div>
		</div>

		<div class="col-sm-6">
			<img src="https://www.ideal.nl/img/statisch/iDEAL-Payoff-2-klein.gif" />
		</div>
	</div>

	<h3>Overige informatie</h3>
	<div class="row">
		<div class="col-sm-12 form-group">
			<b>Hoe bent u bij Anderwijs terechtgekomen?</b><br />
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
		{!! Form::textarea('opmerkingen', null, ['class' => 'form-control', 'placeholder' => 'Denk bijvoorbeeld aan
		speciale diëten, allergieën, medicijnen of andere dingen waar rekening mee gehouden moet worden op kamp.' ]) !!}
	</div>

	<div class="row">
		<div class="col-sm-12">
			<div class="checkbox">
				<label>
					{!! Form::checkbox('voorwaarden', 1) !!} Ik ga akkoord met de <a
						href="http://www.anderwijs.nl/algemene-voorwaarden/" target="_blank">algemene voorwaarden</a>
					voor deelname aan dit Anderwijskamp.
				</label>
			</div>
			<div class="checkbox">
				<label>
					{!! Form::checkbox('privacy', 1) !!} Ik geef Anderwijs toestemming om deze gegevens te verwerken
					zoals beschreven in het <a href="http://www.anderwijs.nl/anderwijs/privacy/"
						target="_blank">privacystatement</a>.
				</label>
			</div>
		</div>
	</div>

	<br />

	<div class="form-group">
		<button class="btn btn-primary form-control" type="submit">Inschrijven</button>
	</div>

	{!! Form::close() !!}

	@endsection


	@section("script")
	<script type="text/javascript">
		var allPackages = {!! $packages->toJSON() !!};

	var campType = {!! $package_type_per_camp->toJSON() !!};

	function checkpackages() {
		
		var selectedCamp = $("#selected_camp").val();
		var packageType = campType[selectedCamp];
		var packages = allPackages[packageType];

		var jPackage = $("#selected_package"); 
		var jPackageContainer = $("#selected_package_container"); 


		if(!packageType) {
			jPackage.empty();
			jPackageContainer.hide();
		} else {
			jPackage.empty();
			jPackage.html(packages.map(function(p) {
				return "<option value='" + p.id + "'>" + p.title  + " (&euro; " + p.price + ")</option>";
			}).join());
			jPackageContainer.show();
		}
	}


	checkpackages();
	
	</script>
	<script type="text/javascript">
		$(function() {
    $('.btn').click(function() {
        setTimeout(function(){$(this).attr('disabled','disabled')},50);
    });
});
	</script>
	@endsection