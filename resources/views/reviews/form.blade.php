<!DOCTYPE html>
<html lang="nl">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Enquête {{ $event->naam }} {{ $event->datum_start->format('Y') }}</title>
	<link rel="icon" type="image/png" href="https://aas2.anderwijs.nl/icon-bait.png">

	<!-- Load Bootstrap theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/flatly/bootstrap.min.css">

	<!-- Load Font Awesome -->
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

	<!-- Load Select2 -->
	<!--
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.9/select2-bootstrap.min.css" />
	-->

	<style type="text/css">
		.form-group-lg>label {
			font-size: 20px;
		}

		h4 {
			font-weight: bold;
			font-size: 20px;
		}

		h4 small {
			color: gray;
		}

		.form-group-lg,
		.radio-buttons {
			margin-bottom: 30px;
		}

		.tab-pane {
			margin-top: 20px;
		}

		h2 {
			font-size: 28px;
		}

		label input[type="radio"],
		label input[type="checkbox"] {
			display: none;
		}

		.radio-buttons label,
		.checkboxes label {
			display: flex;
			align-items: center;
			font-size: 19px;
			font-weight: normal;
		}

		label input[type="radio"]~i.fa.fa-circle-o {
			display: inline;
		}

		label input[type="radio"]~i.fa.fa-dot-circle-o {
			display: none;
		}

		label input[type="radio"]:checked~i.fa.fa-circle-o {
			display: none;
		}

		label input[type="radio"]:checked~i.fa.fa-dot-circle-o {
			display: inline;
		}

		.fa.fa-circle-o,
		.fa.fa-dot-circle-o {
			margin-right: 10px;
		}



		label input[type="checkbox"]~i.fa.fa-square-o {
			display: inline;
		}

		label input[type="checkbox"]~i.fa.fa-check-square-o {
			display: none;
		}

		label input[type="checkbox"]:checked~i.fa.fa-square-o {
			display: none;
		}

		label input[type="checkbox"]:checked~i.fa.fa-check-square-o {
			display: inline;
		}

		.fa.fa-square-o {
			margin-right: 15.5px;
		}

		.fa.fa-check-square-o {
			margin-right: 10px;
		}
	</style>

</head>

<body style="padding-top:20px;">
	<div class="container">

		<h1 id="page-title">Deelnemerenquête Anderwijs {{ $event->naam }} {{ $event->datum_start->format('Y') }}</h1>

		<p id="msg" class="well">
			Wij zijn benieuwd naar je mening over ons kamp! We hopen dat je ons eerlijk antwoord geeft &mdash; het wordt
			<strong>volledig anoniem</strong> verwerkt.<br />Vragen met een sterretje (*) zijn verplicht.
		</p>

		@include ('errors.list')

		{!! Form::open(['url' => ['enquete', $event->id]]) !!}

		<hr />
		<h2>Bijspijkeren</h2>
		<hr />

		<div class="form-group form-group-lg">
			<label for="bs-uren">Hoeveel uur heb je gemiddeld per dag bijgespijkerd (volgens het rooster)? *</label>
			<input type="number" min="1" max="10" step="0.01" class="form-control" id="bs-uren" name="bs-uren"
				value="{{ old('bs-uren') }}">
		</div>

		<h4>Hoe vond je het aantal bijspijkeruren? *</h4>
		<div class="radio-buttons">
			<label>
				<input type="radio" name="bs-mening" value="1" {{ (old('bs-mening')=="1") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Te weinig
			</label>
			<label>
				<input type="radio" name="bs-mening" value="2" {{ (old('bs-mening')=="2") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Voldoende
			</label>
			<label>
				<input type="radio" name="bs-mening" value="3" {{ (old('bs-mening')=="3") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Teveel
			</label>
		</div>

		<h4>Hoe tevreden ben je over wat je qua bijspijkeren hebt bereikt? *</h4>
		<div class="radio-buttons">
			<label>
				<input type="radio" name="bs-tevreden" value="1" {{ (old('bs-tevreden')=="1") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Erg ontevreden
			</label>
			<label>
				<input type="radio" name="bs-tevreden" value="2" {{ (old('bs-tevreden')=="2") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Een beetje ontevreden
			</label>
			<label>
				<input type="radio" name="bs-tevreden" value="3" {{ (old('bs-tevreden')=="3") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Een beetje tevreden
			</label>
			<label>
				<input type="radio" name="bs-tevreden" value="4" {{ (old('bs-tevreden')=="4") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Erg tevreden
			</label>
		</div>

		<h4>Heb je de stof op een andere manier behandeld gekregen dan op school? *</h4>
		<div class="radio-buttons">
			<label>
				<input type="radio" name="bs-manier" value="1" {{ (old('bs-manier')=="1") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Ja
			</label>
			<label>
				<input type="radio" name="bs-manier" value="0" {{ (old('bs-manier')=="0") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Nee
			</label>
		</div>

		<div class="form-group form-group-lg">
			<label for="bs-manier-mening">Zo ja, hoe vond je dit?</label>
			<input type="text" class="form-control" id="bs-manier-mening" name="bs-manier-mening"
				value="{{ old('bs-manier-mening') }}">
		</div>

		<h4>Heb je themablokken gehad op dit kamp? *<br /><small>Bijvoorbeeld over geconcentreerd werken of leren leren.
				Gaat dus niet over het thema van het kamp.</small></h4>
		<div class="radio-buttons">
			<label>
				<input type="radio" name="bs-thema" value="1" {{ (old('bs-thema')=="1") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Ja
			</label>
			<label>
				<input type="radio" name="bs-thema" value="0" {{ (old('bs-thema')=="0") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Nee
			</label>
		</div>

		<div class="form-group form-group-lg">
			<label for="bs-thema-wat">Zo ja, waarover, en wat vond je ervan?</label>
			<input type="text" class="form-control" id="bs-thema-wat" name="bs-thema-wat"
				value="{{ old('bs-thema-wat') }}">
		</div>

		<hr />
		<h2>Leiding</h2>
		<hr />

		<div class="checkboxes">
			<h4>Vink <u>alle</u> leiding aan van wie je op dit kamp bijles hebt gehad.<br /><small>Vragen over deze
					leiding verschijnen daarna hieronder.</small></h4>

			@foreach ($members as $m)
			<label>
				<input type="checkbox" id="leden-{{$m->id}}" name="leden[]" value="{{$m->id}}" class="checkbox-leden"
					{{ ((old('leden')!=null)&&in_array($m->id, old('leden'))) ? 'checked' : '' }}><i
					class="fa fa-square-o fa-2x"></i><i class="fa fa-check-square-o fa-2x"></i>
				{{ $m->volnaam }}
			</label>
			@endforeach
		</div>

		@foreach ($members as $m)
		<div id="member-{{$m->id}}" class="member-div" style="display:none;">

			<hr />

			<h2>Vragen over {{$m->volnaam}}</h2>

			<div class="form-group form-group-lg">
				<h4>Hoe legt {{ ($m->geslacht == 'M') ? 'hij' : 'zij' }} de stof uit? *</h4>
				<div class="radio-buttons">
					<label>
						<input type="radio" name="stof-{{$m->id}}" value="1"
							{{ (old('stof-'.$m->id)=="1") ? 'checked' : '' }}>
						<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
						Zeer slecht
					</label>
					<label>
						<input type="radio" name="stof-{{$m->id}}" value="2"
							{{ (old('stof-'.$m->id)=="2") ? 'checked' : '' }}>
						<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
						Slecht
					</label>
					<label>
						<input type="radio" name="stof-{{$m->id}}" value="3"
							{{ (old('stof-'.$m->id)=="3") ? 'checked' : '' }}>
						<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
						Gewoon
					</label>
					<label>
						<input type="radio" name="stof-{{$m->id}}" value="4"
							{{ (old('stof-'.$m->id)=="4") ? 'checked' : '' }}>
						<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
						Goed
					</label>
					<label>
						<input type="radio" name="stof-{{$m->id}}" value="5"
							{{ (old('stof-'.$m->id)=="5") ? 'checked' : '' }}>
						<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
						Zeer goed
					</label>
				</div>
			</div>

			<div class="form-group form-group-lg">
				<h4>Hoeveel aandacht gaf {{ ($m->geslacht == 'M') ? 'hij' : 'zij' }} je tijdens de blokjes? *</h4>
				<div class="radio-buttons">
					<label>
						<input type="radio" name="aandacht-{{$m->id}}" value="1"
							{{ (old('aandacht-'.$m->id)=="1") ? 'checked' : '' }}>
						<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
						Te weinig
					</label>
					<label>
						<input type="radio" name="aandacht-{{$m->id}}" value="2"
							{{ (old('aandacht-'.$m->id)=="2") ? 'checked' : '' }}>
						<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
						Weinig
					</label>
					<label>
						<input type="radio" name="aandacht-{{$m->id}}" value="3"
							{{ (old('aandacht-'.$m->id)=="3") ? 'checked' : '' }}>
						<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
						Voldoende
					</label>
					<label>
						<input type="radio" name="aandacht-{{$m->id}}" value="4"
							{{ (old('aandacht-'.$m->id)=="4") ? 'checked' : '' }}>
						<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
						Veel
					</label>
				</div>
			</div>

			<div class="form-group form-group-lg">
				<h4>Hoe vond je het om door {{ ($m->geslacht == 'M') ? 'hem' : 'haar' }} bijgespijkerd te worden? *</h4>
				<div class="radio-buttons">
					<label>
						<input type="radio" name="mening-{{$m->id}}" value="1"
							{{ (old('mening-'.$m->id)=="1") ? 'checked' : '' }}>
						<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
						Zeer vervelend
					</label>
					<label>
						<input type="radio" name="mening-{{$m->id}}" value="2"
							{{ (old('mening-'.$m->id)=="2") ? 'checked' : '' }}>
						<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
						Vervelend
					</label>
					<label>
						<input type="radio" name="mening-{{$m->id}}" value="3"
							{{ (old('mening-'.$m->id)=="3") ? 'checked' : '' }}>
						<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
						Gewoon
					</label>
					<label>
						<input type="radio" name="mening-{{$m->id}}" value="4"
							{{ (old('mening-'.$m->id)=="4") ? 'checked' : '' }}>
						<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
						Prettig
					</label>
					<label>
						<input type="radio" name="mening-{{$m->id}}" value="5"
							{{ (old('mening-'.$m->id)=="5") ? 'checked' : '' }}>
						<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
						Zeer prettig
					</label>
				</div>
			</div>

			<div class="form-group form-group-lg">
				<h4>Hoe tevreden ben je over wat je met {{ ($m->geslacht == 'M') ? 'hem' : 'haar' }} hebt bereikt? *
				</h4>
				<div class="radio-buttons">
					<label>
						<input type="radio" name="tevreden-{{$m->id}}" value="1"
							{{ (old('tevreden-'.$m->id)=="1") ? 'checked' : '' }}>
						<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
						Erg ontevreden
					</label>
					<label>
						<input type="radio" name="tevreden-{{$m->id}}" value="2"
							{{ (old('tevreden-'.$m->id)=="2") ? 'checked' : '' }}>
						<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
						Een beetje ontevreden
					</label>
					<label>
						<input type="radio" name="tevreden-{{$m->id}}" value="3"
							{{ (old('tevreden-'.$m->id)=="3") ? 'checked' : '' }}>
						<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
						Een beetje tevreden
					</label>
					<label>
						<input type="radio" name="tevreden-{{$m->id}}" value="4"
							{{ (old('tevreden-'.$m->id)=="4") ? 'checked' : '' }}>
						<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
						Erg tevreden
					</label>
				</div>
			</div>

			<div class="form-group form-group-lg">
				<label for="bericht-{{$m->id}}">Wil je nog iets persoonlijk kwijt aan
					{{$m->voornaam}}?<br /><small>Alleen {{ ($m->geslacht == 'M') ? 'hij' : 'zij' }} kan dit bericht
						zien.</small></label>
				<input type="text" class="form-control" id="bericht-{{$m->id}}" name="bericht-{{$m->id}}"
					value="{{ old('bericht-'.$m->id) }}">
			</div>

		</div>
		@endforeach

		<hr />
		<h2>Kamphuis</h2>
		<hr />

		<h4>Wat vond je van de slaapruimtes in het kamphuis? *</h4>
		<div class="radio-buttons">
			<label>
				<input type="radio" name="kh-slaap" value="1" {{ (old('kh-slaap')=="1") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Slecht
			</label>
			<label>
				<input type="radio" name="kh-slaap" value="2" {{ (old('kh-slaap')=="2") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Onvoldoende
			</label>
			<label>
				<input type="radio" name="kh-slaap" value="3" {{ (old('kh-slaap')=="3") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Voldoende
			</label>
			<label>
				<input type="radio" name="kh-slaap" value="4" {{ (old('kh-slaap')=="4") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Goed
			</label>
		</div>

		<div class="form-group form-group-lg">
			<label for="kh-slaap-wrm">Waarom?</label>
			<input type="text" class="form-control" id="kh-slaap-wrm" name="kh-slaap-wrm"
				value="{{ old('kh-slaap-wrm') }}">
		</div>

		<h4>Wat vond je van de bijspijkerruimtes in het kamphuis? *</h4>
		<div class="radio-buttons">
			<label>
				<input type="radio" name="kh-bijspijker" value="1" {{ (old('kh-bijspijker')=="1") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Slecht
			</label>
			<label>
				<input type="radio" name="kh-bijspijker" value="2" {{ (old('kh-bijspijker')=="2") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Onvoldoende
			</label>
			<label>
				<input type="radio" name="kh-bijspijker" value="3" {{ (old('kh-bijspijker')=="3") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Voldoende
			</label>
			<label>
				<input type="radio" name="kh-bijspijker" value="4" {{ (old('kh-bijspijker')=="4") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Goed
			</label>
		</div>

		<div class="form-group form-group-lg">
			<label for="kh-bijspijker-wrm">Waarom?</label>
			<input type="text" class="form-control" id="kh-bijspijker-wrm" name="kh-bijspijker-wrm"
				value="{{ old('kh-bijspijker-wrm') }}">
		</div>

		<h4>Wat vond je van het kamphuis als geheel? *</h4>
		<div class="radio-buttons">
			<label>
				<input type="radio" name="kh-geheel" value="1" {{ (old('kh-geheel')=="1") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Slecht
			</label>
			<label>
				<input type="radio" name="kh-geheel" value="2" {{ (old('kh-geheel')=="2") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Onvoldoende
			</label>
			<label>
				<input type="radio" name="kh-geheel" value="3" {{ (old('kh-geheel')=="3") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Voldoende
			</label>
			<label>
				<input type="radio" name="kh-geheel" value="4" {{ (old('kh-geheel')=="4") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Goed
			</label>
		</div>

		<div class="form-group form-group-lg">
			<label for="kh-geheel-wrm">Waarom?</label>
			<input type="text" class="form-control" id="kh-geheel-wrm" name="kh-geheel-wrm"
				value="{{ old('kh-geheel-wrm') }}">
		</div>

		<hr />
		<h2>Overig</h2>
		<hr />

		<div class="form-group form-group-lg">
			<label for="leidingploeg">Wat vond je van de leidingploeg? *</label>
			<input type="text" class="form-control" id="leidingploeg" name="leidingploeg"
				value="{{ old('leidingploeg') }}">
		</div>

		<h4>Hoeveel tijd had je om te slapen? *</h4>
		<div class="radio-buttons">
			<label>
				<input type="radio" name="slaaptijd" value="1" {{ (old('slaaptijd')=="1") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Veel te weinig
			</label>
			<label>
				<input type="radio" name="slaaptijd" value="2" {{ (old('slaaptijd')=="2") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Weinig
			</label>
			<label>
				<input type="radio" name="slaaptijd" value="3" {{ (old('slaaptijd')=="3") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Genoeg
			</label>
			<label>
				<input type="radio" name="slaaptijd" value="4" {{ (old('slaaptijd')=="4") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Meer dan genoeg
			</label>
		</div>

		<div class="form-group form-group-lg">
			<label for="slaaptijd-hoe">Als je (veel te) weinig tijd had om te slapen, hoe kwam dat dan?</label>
			<input type="text" class="form-control" id="slaaptijd-hoe" name="slaaptijd-hoe"
				value="{{ old('slaaptijd-hoe') }}">
		</div>

		<h4>Wat vond je van de lengte van het kamp? *</h4>
		<div class="radio-buttons">
			<label>
				<input type="radio" name="kamplengte" value="1" {{ (old('kamplengte')=="1") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Veel te kort
			</label>
			<label>
				<input type="radio" name="kamplengte" value="2" {{ (old('kamplengte')=="2") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Te kort
			</label>
			<label>
				<input type="radio" name="kamplengte" value="3" {{ (old('kamplengte')=="3") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Precies goed
			</label>
			<label>
				<input type="radio" name="kamplengte" value="4" {{ (old('kamplengte')=="4") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Te lang
			</label>
			<label>
				<input type="radio" name="kamplengte" value="5" {{ (old('kamplengte')=="5") ? 'checked' : '' }}>
				<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
				Veel te lang
			</label>
		</div>

		<div class="form-group form-group-lg">
			<label for="kamplengte-wrm">Waarom?</label>
			<input type="text" class="form-control" id="kamplengte-wrm" name="kamplengte-wrm"
				value="{{ old('kamplengte-wrm') }}">
		</div>

		<div class="form-group form-group-lg">
			<label for="eten">Wat vond je van het eten tijdens het kamp? *</label>
			<input type="text" class="form-control" id="eten" name="eten" value="{{ old('eten') }}">
		</div>

		<div class="form-group form-group-lg">
			<label for="avond-leukst">Welk avondprogramma vond je het leukst en waarom? *</label>
			<input type="text" class="form-control" id="avond-leukst" name="avond-leukst"
				value="{{ old('avond-leukst') }}">
		</div>

		<div class="form-group form-group-lg">
			<label for="avond-minstleuk">Welk avondprogramma vond je het minst leuk en waarom? *</label>
			<input type="text" class="form-control" id="avond-minst" name="avond-minst"
				value="{{ old('avond-minst') }}">
		</div>

		<div class="form-group form-group-lg">
			<label for="allerleukst">Wat is het allerleukste dat je hebt gedaan of dat is gebeurd tijdens het kamp?
				*</label>
			<input type="text" class="form-control" id="allerleukst" name="allerleukst"
				value="{{ old('allerleukst') }}">
		</div>

		<div class="form-group form-group-lg">
			<label for="allervervelendst">Wat is het allervervelendste dat is gebeurd tijdens het kamp? *</label>
			<input type="text" class="form-control" id="allervervelendst" name="allervervelendst"
				value="{{ old('allervervelendst') }}">
		</div>

		<div class="form-group form-group-lg">
			<label for="cijfer">Geef een schoolcijfer voor dit kamp als geheel. *</label>
			<input type="number" min="1" max="10" step="0.1" class="form-control" id="cijfer" name="cijfer"
				value="{{ old('cijfer') }}">
		</div>

		<div class="form-group form-group-lg">
			<label for="herhaling">Zou je nog eens mee willen op kamp (en waarom)? *</label>
			<input type="text" class="form-control" id="nogeens" name="nogeens" value="{{ old('nogeens') }}">
		</div>

		<div class="checkboxes">
			<h4>Anderwijs wil graag de bijleskampen op de meest geschikte momenten in het jaar organiseren. Als je nog
				eens op kamp zou gaan, aan welk kamp / welke kampen zou je dan het liefst
				deelnemen?<br /><small>Meerdere antwoorden mogelijk</small></h4>

			@foreach (\App\Models\Event::CAMP_DESCRIPTIONS as $k => $v)
			<label>
				<input type="checkbox" id="kampkeuze-{{$k}}" name="kampkeuze[]" value="{{$k}}"
					class="checkbox-kampkeuze"
					{{ ((old('kampkeuze')!=null)&&in_array($k, old('kampkeuze'))) ? 'checked' : '' }}><i
					class="fa fa-square-o fa-2x"></i><i class="fa fa-check-square-o fa-2x"></i>
				{{ $v }}
			</label>
			@endforeach

			<label>
				<input type="checkbox" id="kampkeuze-0" name="kampkeuze[]" value="0" class="checkbox-kampkeuze"
					{{ ((old('kampkeuze')!=null)&&in_array('0', old('kampkeuze'))) ? 'checked' : '' }}><i
					class="fa fa-square-o fa-2x"></i><i class="fa fa-check-square-o fa-2x"></i>
				<span style="white-space: nowrap;">Anders, namelijk: &nbsp;</span><input type="text"
					class="form-control" id="kampkeuze_anders" name="kampkeuze_anders"
					value="{{ old('kampkeuze_anders') }}">
			</label>

		</div>

		<br />

		<div class="form-group form-group-lg">
			<label for="tip">Heb je nog een tip voor Anderwijs als organisatie?</label>
			<input type="text" class="form-control" id="tip" name="tip" value="{{ old('tip') }}">
		</div>

		<div class="form-group form-group-lg">
			<label for="verder">Wil je verder nog iets kwijt?</label>
			<input type="text" class="form-control" id="verder" name="verder" value="{{ old('verder') }}">
		</div>

		<button class="btn btn-primary btn-lg" type="submit">Versturen</button>

		{!! Form::close() !!}

	</div>

	<!-- Javascript -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
		// Render member questions on selection
		$(".checkbox-leden").change(function() {
			$(".member-div").hide();
			$(".checkbox-leden:checked").each(function() {
				var member_id = $(this).val();
				$("#member-"+member_id).show();
			});
		});

		// Run once to ensure proper viewing of old selection
		$(".checkbox-leden").change();

	});
	</script>
</body>

</html>
