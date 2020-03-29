@extends('master')

@section('title')
	Op kamp
@endsection


@section('content')
<!-- Dit is het formulier voor het op kamp gaan vanuit je profiel -->

<h1>Op kamp</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'profile/on-camp', 'method' => 'PUT']) !!}

<div class="row">
	<div class="col-sm-6">
		<div class="well">
			<strong>Let op! </strong>
			@if (\Auth::user()->profile_type == 'App\Participant')
				Zijn alle gegevens in uw profiel up-to-date? Met name voor het <strong>niveau</strong> en de <strong>klas</strong> van de deelnemer is het erg belangrijk dat deze informatie klopt, omdat er anders mogelijk geen volledige dekking is voor de vakken die u opgeeft. Ook uw <strong>bruto maandinkomen</strong> is van belang voor correcte betalingsinformatie.
			@elseif (\Auth::user()->profile_type == 'App\Member')
				Zijn alle gegevens in je profiel up-to-date?
			@endif
		</div>

		<h3>Kamp</h3>
		@if (\Auth::user()->profile_type == 'App\Participant')
			<div class="well well-sm">
				Gaat uw kind op zomerkamp? Geef dan bij klas het huidige schooljaar op, dus niet de klas waar uw kind naartoe gaat. Let er ook op dat uw kind zelf schoolboeken moet meenemen!
			</div>
		@endif
		<div class="form-group">
			<select class="form-control" id="selected_camp" name="selected_camp" onchange="checkpackages()">
				<?php foreach ($camps as $camp) { ?>
					<option value="{{$camp->id}}" {{ ($camp->vol) ? 'disabled' : '' }} >{{$camp->full_title}}</option>
				<?php } ?>
			</select>
		</div>
	</div>
</div>

@if (\Auth::user()->profile_type == 'App\Participant')

	<div class="row">
		<div class="col-sm-6" style="display: none" id="selected_package_container">
			<h3>Pakket</h3>
			<div class="well">
				Bij dit kamp is er keuze uit meerdere pakketten. Maak een keuze uit een van de volgende opties:
			</div>
			<div class="form-group">
				<select class="form-control" id="selected_package" name="selected_package">
					
				</select>
			</div>
		</div>
	</div>

	<h3>Vakken</h3>
	<div class="row">
		<div class="col-sm-9">
			<div class="well">
				Hieronder kunt de vakken waar op kamp aandacht aan moet worden besteed, opgeven. Bij ieder vak kunt u in het tekstvak ernaast extra informatie opgeven, zoals de onderwerpen en moeilijkheden in kwestie.
			</div>
		</div>
	</div>

	@for ($i = 0; $i < 6; $i++)
	<div class="row">
		<div class="col-sm-3 form-group">
			<select class="form-control" name="vak[]">
				@foreach ($course_options as $id => $course)
					<option value="{{$id}}">{{$course}}</option>
				@endforeach
			</select>
		</div>
		
		<div class="col-sm-6 form-group">
			<textarea class="form-control" name="vakinfo[]"></textarea>
		</div>
	</div>
	@endfor
	
	<h3>Betaalmethode</h3>
	<div class="row">
		<div class="col-sm-6 form-group" style="margin-bottom:30px;">
			<div class="radio">
				 <label>
					{!! Form::radio('iDeal', 1, true) !!}
					<b>Direct betalen met iDeal</b>
				 </label>
			</div>
			<div class="radio">
				 <label>
						{!! Form::radio('iDeal', 0) !!}
					Per bankoverschrijving (instructies in de bevestigingsmail)
				 </label>
			</div>
		</div>
		
		<div class="col-sm-6">
			<img src="https://www.ideal.nl/img/statisch/iDEAL-Payoff-2-klein.gif" />
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-6">
			<div class="well">
				<strong>Let op! </strong>Bij aanmelding gaat u automatisch akkoord met onze <a href="https://www.anderwijs.nl/algemene-voorwaarden/" target="_blank">algemene voorwaarden</a> (link wordt geopend in een nieuw venster).
				<br/><br/>
				U ontvangt automatisch een bevestiging per email.
			</div>
		</div>
	</div>
@endif

<div class="row">
	<div class="col-sm-6 form-group">
		{!! Form::submit('Aanmelden', ['class' => 'btn btn-primary form-control']) !!}
	</div>
</div>

{!! Form::close() !!}
@endsection

@section("script")
<script>

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
				return "<option value='" + p.id + "'>" + p.title  + ": (&euro; " + p.price + ") " + p.description + "</option>";
			}).join());
			jPackageContainer.show();
		}
	}


	checkpackages();
	
</script>
@endsection