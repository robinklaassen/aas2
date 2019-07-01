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
		@if (\Auth::user()->profile_type == 'App\Participant')
			<div class="well well-sm">
				Gaat uw kind op zomerkamp? Geef dan bij klas het huidige schooljaar op, dus niet de klas waar uw kind naartoe gaat. Let er ook op dat uw kind zelf schoolboeken moet meenemen!
			</div>
		@endif
		<div class="form-group">
			{!! Form::label('selected_camp', 'Kamp:') !!}
			<select class="form-control" id="selected_camp" name="selected_camp">
				<?php foreach ($camp_options as $id => $name) { ?>
					<option value="{{$id}}" {{ ($camp_full[$id]) ? 'disabled' : '' }} >{{$name}}</option>
				<?php } ?>
			</select>
		</div>
	</div>
</div>

@if (\Auth::user()->profile_type == 'App\Participant')
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
					{!! Form::radio('ideal', 1, true) !!}
					<b>Direct betalen met iDeal</b>
				 </label>
			</div>
			<div class="radio">
				 <label>
						{!! Form::radio('ideal', 0) !!}
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