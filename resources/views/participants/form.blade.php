<h3>Profiel</h3>

<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('voornaam', 'Voornaam:') !!}
		{!! Form::text('voornaam', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::label('tussenvoegsel', 'Tussenvoegsel:') !!}
		{!! Form::text('tussenvoegsel', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-6 form-group">
		{!! Form::label('achternaam', 'Achternaam:') !!}
		{!! Form::text('achternaam', null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('geboortedatum', 'Geboortedatum:') !!}
		@if (isset($participant))
		{!! Form::input('date', 'geboortedatum', $participant->geboortedatum->format('Y-m-d'), ['class' => 'form-control', 'placeholder' => 'Format: jjjj-mm-dd']) !!}
		@else
		{!! Form::input('date', 'geboortedatum', null, ['class' => 'form-control', 'placeholder' => 'Format: jjjj-mm-dd']) !!}
		@endif
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
	<div class="col-sm-4 form-group">
		{!! Form::label('adres', 'Adres:') !!}
		{!! Form::text('adres', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::label('postcode', 'Postcode:') !!}
		{!! Form::text('postcode', null, ['class' => 'form-control', 'placeholder' => 'Format: 0000 AA']) !!}
	</div>

	<div class="col-sm-6 form-group">
		{!! Form::label('plaats', 'Woonplaats:') !!}
		{!! Form::text('plaats', null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('telefoon_ouder_vast', 'Telefoonnummer ouder (vast):') !!}
		{!! Form::text('telefoon_ouder_vast', null, ['class' => 'form-control', 'maxlength' => 10, 'placeholder' => '10 cijfers']) !!}
	</div>

	<div class="col-sm-4 form-group">
		{!! Form::label('telefoon_ouder_mobiel', 'Telefoonnummer ouder (mobiel):') !!}
		{!! Form::text('telefoon_ouder_mobiel', null, ['class' => 'form-control', 'maxlength' => 10, 'placeholder' => '10 cijfers']) !!}
	</div>

	<div class="col-sm-4 form-group">
		{!! Form::label('telefoon_deelnemer', 'Telefoonnummer deelnemer:') !!}
		{!! Form::text('telefoon_deelnemer', null, ['class' => 'form-control', 'maxlength' => 10, 'placeholder' => '10 cijfers']) !!}
	</div>
</div>


<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('email_ouder', 'Emailadres ouder:') !!}
		{!! Form::email('email_ouder', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-4 form-group">
		{!! Form::label('email_deelnemer', 'Emailadres deelnemer:') !!}
		{!! Form::email('email_deelnemer', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-2 form-group">
		<label for="mag_gemaild">
			Mailing ontvangen
			<span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" title="Dit gaat alleen om nieuwsbrieven en kortingsacties. Bij deelname aan een kamp ontvangt u altijd mail.">
		</label>
		{!! Form::select('mag_gemaild', [0 => "Nee", 1=> "Ja"], 0, ['class' => 'form-control']) !!}
		<i class="glyph">
	</div>

	<div class="col-sm-2 form-group">
		<label for="post">
			Kamp informatie 
			<span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" title="Informatie over uw kamp deelname worden met u gedeeld via email en post.">
		</label>
		{!! Form::select('information_channel', \App\Participant::INFORMATION_CHANNEL, 0, ['class' => 'form-control']) !!}
	</div>

</div>

<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('school', 'Naam school:') !!}
		{!! Form::text('school', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::label('niveau', 'Niveau:') !!}
		{!! Form::select('niveau', ['VMBO' => 'VMBO', 'HAVO' => 'HAVO', 'VWO' => 'VWO'], null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::label('klas', 'Klas:') !!}
		{!! Form::input('number', 'klas', null, ['class' => 'form-control', 'min' => 1, 'max' => 6, 'step' => 1]) !!}
	</div>

	<div class="col-sm-4 form-group">
		{!! Form::label('hoebij', 'Hoe bij Anderwijs?') !!}
		{!! Form::text('hoebij', null, ['class' => 'form-control']) !!}
	</div>
</div>

@if ($viewType == 'admin')

<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('inkomen', 'Bruto maandinkomen:') !!}
		{!! Form::select('inkomen', ['Meer dan € 3400 (geen korting)', 'Tussen € 2200 en € 3400 (korting: 15%)', 'Tussen € 1300 en € 2200 (korting: 30%)', 'Minder dan € 1300 (korting: 50%)'], null, ['class' => 'form-control']) !!}
	</div>
</div>

@endif

<div class="form-group">
	{!! Form::label('opmerkingen', 'Overige informatie:') !!}
	{!! Form::textarea('opmerkingen', null, ['class' => 'form-control']) !!}
</div>

@if ($viewType == 'admin')

<h3>Administratie</h3>

<div class="row">
	<div class="col-sm-5 form-group">
		{!! Form::label('inkomensverklaring', 'Inkomensverklaring binnen op:') !!}
		@if (isset($participant) && $participant->inkomensverklaring)
		{!! Form::input('date', 'inkomensverklaring', $participant->inkomensverklaring->toDateString(), ['class' => 'form-control', 'placeholder' => 'Format: jjjj-mm-dd']) !!}
		@else
		{!! Form::input('date', 'inkomensverklaring', null, ['class' => 'form-control', 'placeholder' => 'Format: jjjj-mm-dd']) !!}
		@endif
	</div>
</div>

@endif

<div class="form-group">
	{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
</div>