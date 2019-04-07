@extends('master')

@section('title')
	Declaratie indienen
@endsection

@section('header')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('content')
<!-- Dit is het formulier voor het doen van een declaratie -->

@if (\Auth::user()->profile->iban == null)
	<div class="alert alert-danger alert-important">
		Vul eerst een rekeningnummer in op je profiel, daarna mag je dit gebruiken!
	</div>
@else
	<!--
	<div class="alert alert-warning alert-important">
		Er vindt op dit moment technisch onderhoud plaats aan dit formulier!
	</div>
	-->
@endif

<h1>Declaratie indienen</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'profile/declare', 'method' => 'PUT', 'files' => true]) !!}
		
<h3>Stap 1: bestanden uploaden</h3>

<div class="well">
	Hier kun je je (digitale) bonnetjes uploaden. Een foto van een bon mag ook, maar zorg dat deze goed leesbaar is.
	<br/><br/>
	Let op dat je weet welk bestand je bij welk nummer uploadt, dat is belangrijk voor het invullen van de omschrijvingen! Om jezelf daarbij te helpen kun je in het tekstvak 'aanduiding' iets neerzetten om het voor jezelf helder te maken, dit is niet verplicht en er wordt verder niets mee gedaan.
</div>

<table class="table table-hover">
<thead>
	<tr>
		<th>#</th>
		<th>Bestand</th>
		<th>Aanduiding</th>
	</tr>
</thead>
<tbody>
@for ($i = 0; $i < 6; $i++)
	<tr>
		<td style="vertical-align:middle;">{{$i+1}}</td>
		<td>
			<input type="file" name="uploaded{{$i+1}}">
		</td>
		<td style="vertical-align:middle;">
			<input class="form-control" type="text" name="denotion[]">
		</td>
	</tr>
@endfor
</tbody>
</table>

<h3>Stap 2: informatie opgeven</h3>

<div class="well">
	Hier vul je de informatie in die bij je geüploade bestanden hoort. Zet bij 'bestand #' het nummer neer uit de bovenstaande tabel waar je het revelante bonnetje bij hebt geüpload. Je kunt per bestand meerdere dingen declareren door hetzelfde bestandsnummer in te voeren.
	<br/><br/>
	Wil je iets declareren zonder bon, bijvoorbeeld wanneer je de bon kwijt bent of voor autokilometers, vul dan een 0 (nul) in als nummer.
	<br/><br/>
	<b>Let op</b>: regels waar geen bestandsnummer wordt ingevoerd, worden in het geheel niet meegenomen in de declaratie!
</div>

<table class="table table-hover">
<thead>
	<tr>
		<th class="col-xs-2">Bestand #</th>
		<th class="col-xs-2">Datum</th>
		<th class="col-xs-6">Omschrijving</th>
		<th class="col-xs-2">Bedrag</th>
		<th class="col-xs-1" style="text-align: center;">Gift?</th>
	</tr>
</thead>
<tbody>
@for ($i = 0; $i < 10; $i++)
	<tr>
		<td>
			<input type="number" name="fileNumber[]" min="0" max="10" step="1" class="form-control">
		</td>
		<td>
			<input type="date" name="date[]" class="form-control">
		</td>
		<td>
			<input type="text" name="description[]" class="form-control">
		</td>
		<td>
			<div class="input-group">
				<span class="input-group-addon">€</span>
				<input type="number" name="amount[]" min="0" step="0.01" class="form-control money">
			</div>
		</td>
		<td style="vertical-align:middle; text-align: center;">
			<input type="checkbox" name="gift[{{$i}}]" class="gift">
		</td>
	</tr>
@endfor
	<tr>
		<td></td>
		<td></td>
		<td class="text-right" style="vertical-align: middle;"><b>Totaalbedrag:</b></td>
		<td>
			<div class="input-group">
				<span class="input-group-addon">€</span>
				<input type="number" name="totalAmount" id="totalAmount" min="0" step="0.01" class="form-control" readonly>
			</div>
		</td>
		<td></td>
	</tr>
</tbody>
</table>

<div class="well">
	Voordat je op 'versturen' klikt, controleer het formulier nog een keer goed!
</div>

<div class="form-group">
	<input class="btn btn-primary form-control" type="submit" value="Versturen" @if (\Auth::user()->profile->iban == null) disabled @endif>
</div>

{!! Form::close() !!}

@endsection

@section('footer')
<script type="text/javascript">
$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});
$(document).ready(function(){
	// On input change, count total amount
	$(".money, .gift").change(function() {
		var sum = 0;
		
		var i = 1; var checked = [];
		$(".gift").each(function() {
			checked[i] = ((this).checked) ? 1 : 0;
			i++;
		});
		
		var j = 1;
		$(".money").each(function(){
			var value = Number($(this).val());
			if (!isNaN(value) && !checked[j]) sum += value;
			j++;
		});
		$("#totalAmount").val(sum.toFixed(2));
	});
});
</script>
@endsection