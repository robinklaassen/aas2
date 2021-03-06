<div class="row">
	<div class="col-md-3 form-group">
		{!! Form::label('naam', 'Naam evenement:') !!}
		{!! Form::text('naam', null, ['class' => 'form-control']) !!}
	</div>

	@canany("editAdvanced", \App\Event::class, $event)
	<div class="col-md-2 form-group">
		{!! Form::label('code', 'Code:') !!}
		{!! Form::text('code', null, ['class' => 'form-control']) !!}
	</div>
	@endcanany

	<div class="col-md-5 form-group">
		{!! Form::label('location_id', 'Locatie:') !!}
		{!! Form::select('location_id', $locations, null, ['class' => 'form-control']) !!}
	</div>

	@canany("editAdvanced", \App\Event::class, $event)
	<div class="col-md-2 form-group">
		{!! Form::label('openbaar', 'Openbaar', ['title' => 'Openbare evenementen worden gepubliseerd op de website.']) !!}<br />
		{!! Form::hidden('openbaar', 1) !!}
		{!! Form::checkbox('openbaar', 1, null, ['style' => 'margin-top:14px;']) !!}
	</div>
	@endcanany
</div>

<div class="row">
	<div class="col-md-3 form-group">
		{!! Form::label('datum_start', 'Datum start:') !!}
		{!! Form::input(
		'date', 'datum_start',
		isset($event) ? $event->datum_start->format('Y-m-d') : null,
		['class' => 'form-control', 'placeholder' => 'Format: jjjj-mm-dd'])
		!!}
	</div>

	<div class="col-md-2 form-group">
		{!! Form::label('tijd_start', 'Tijd start:') !!}
		{!! Form::input('time', 'tijd_start', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-md-3 form-group">
		{!! Form::label('datum_eind', 'Datum eind:') !!}
		{!! Form::input(
		'date', 'datum_eind',
		isset($event) ? $event->datum_eind->format('Y-m-d') : null,
		['class' => 'form-control', 'placeholder' => 'Format: jjjj-mm-dd']
		)
		!!}
	</div>

	<div class="col-md-2 form-group">
		{!! Form::label('tijd_eind', 'Tijd eind:') !!}
		{!! Form::input('time', 'tijd_eind', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-md-2 form-group">
		{!! Form::label('cancelled', 'Afgelast', ['title' => 'Afgelaste kampen tellen niet mee in de statistieken en men kan zich er niet voor inschrijven']) !!}<br />
		{!! Form::hidden('cancelled', 0) !!}
		{!! Form::checkbox('cancelled', 1, null, ['style' => 'margin-top:14px;']) !!}
	</div>
</div>
<div class="row">
	<div class="col-md-2 form-group">
		{!! Form::label('type', 'Type evenement:') !!}
		{!! Form::select('type', (\App\Event::class)::TYPE_DESCRIPTIONS, null, ['class' => 'form-control']) !!}
	</div>
	<div class="col-md-2 form-group">
		{!! Form::label('package_type', 'Pakket types:') !!}
		{!! Form::select('package_type', array_merge([null => "Geen"], (\App\EventPackage::class)::TYPE_DESCRIPTIONS),
		null, ['class' => 'form-control']) !!}
	</div>
</div>

<p><strong><br />Kampspecifieke velden (leeglaten indien geen kamp!)</strong></p>
<hr />
<div class="row">
	<div class="col-md-4 form-group">
		{!! Form::label('datum_voordag', 'Start voordag(en):') !!}
		{!! Form::input(
		'date', 'datum_voordag',
		(isset($event) && $event->datum_voordag !== null) ? $event->datum_voordag->format('Y-m-d') : null,
		['class' => 'form-control', 'placeholder' => 'Format: jjjj-mm-dd'])
		!!}
	</div>

	@canany("editAdvanced", \App\Event::class, $event)
	<div class="col-md-4 form-group">
		{!! Form::label('prijs', 'Kampprijs (zonder korting):') !!}
		<div class="input-group">
			<span class="input-group-addon">€</span>
			{!! Form::input('number', 'prijs', null, ['class' => 'form-control', 'min' => 0]) !!}
		</div>
	</div>
	@endcanany

	<div class="col-md-2 form-group">
		{!! Form::label('streeftal', 'Streeftal L / D:') !!}
		{!! Form::select('streeftal', $streeftal_options, null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-md-2 form-group">
		{!! Form::label('vol', 'Kamp vol') !!}<br />
		{!! Form::hidden('vol', 0) !!}
		{!! Form::checkbox('vol', 1, null, ['style' => 'margin-top:14px;']) !!}
	</div>
</div>

@canany("editAdvanced", \App\Event::class, $event)
<div class="row">
	<div class="col-md-12">
		<p class="well">
			Is de kampprijs nog niet vastgesteld? Laat het veld dan leeg! De automatische mails geven dan door dat de
			ouder per mail bericht krijgt wanneer de prijs is vastgesteld.
		</p>
	</div>
</div>
@endcanany

@canany("editAdvanced", \App\Event::class, $event)
<div class="form-group">
	{!! Form::label('beschrijving', 'Beschrijving (website):') !!}
	{!! Form::textarea('beschrijving', null, ['class' => 'form-control', 'rows' => '5']) !!}
</div>
@endcanany

<hr />

<div class="form-group">
	{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
</div>
