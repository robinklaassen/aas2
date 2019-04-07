<div class="row">
	<div class="col-md-3 form-group">
		{!! Form::label('naam', 'Naam evenement:') !!}
		{!! Form::text('naam', null, ['class' => 'form-control']) !!}
	</div>
	
	<div class="col-md-2 form-group">
		{!! Form::label('code', 'Code:') !!}
		{!! Form::text('code', null, ['class' => 'form-control']) !!}
	</div>
	
	<div class="col-md-5 form-group">
		{!! Form::label('location_id', 'Locatie:') !!}
		{!! Form::select('location_id', $locations, null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-md-2 form-group">
		{!! Form::label('openbaar', 'Openbaar?') !!}<br/>
		{!! Form::hidden('openbaar', 1) !!}
		{!! Form::checkbox('openbaar', 1, null, ['style' => 'margin-top:14px;']) !!}
	</div>
</div>

<div class="row">
	<div class="col-md-3 form-group">
		{!! Form::label('datum_start', 'Datum start:') !!}
		@if (isset($event))
			{!! Form::input('date', 'datum_start', $event->datum_start->format('Y-m-d'), ['class' => 'form-control', 'placeholder' => 'Format: jjjj-mm-dd']) !!}
		@else
			{!! Form::input('date', 'datum_start', null, ['class' => 'form-control', 'placeholder' => 'Format: jjjj-mm-dd']) !!}
		@endif
	</div>
	
	<div class="col-md-2 form-group">
		{!! Form::label('tijd_start', 'Tijd start:') !!}
		{!! Form::input('time', 'tijd_start', null, ['class' => 'form-control']) !!}
	</div>
	
	<div class="col-md-3 form-group">
		{!! Form::label('datum_eind', 'Datum eind:') !!}
		@if (isset($event))
			{!! Form::input('date', 'datum_eind', $event->datum_eind->format('Y-m-d'), ['class' => 'form-control', 'placeholder' => 'Format: jjjj-mm-dd']) !!}
		@else
			{!! Form::input('date', 'datum_eind', null, ['class' => 'form-control', 'placeholder' => 'Format: jjjj-mm-dd']) !!}
		@endif
	</div>
	
	<div class="col-md-2 form-group">
		{!! Form::label('tijd_eind', 'Tijd eind:') !!}
		{!! Form::input('time', 'tijd_eind', null, ['class' => 'form-control']) !!}
	</div>
	
	<div class="col-md-2 form-group">
		{!! Form::label('type', 'Type evenement:') !!}
		{!! Form::select('type', ['kamp' => 'Kamp', 'training' => 'Training', 'overig' => 'Overig'], null, ['class' => 'form-control']) !!}
	</div>
</div>

<p><strong><br/>Kampspecifieke velden (leeglaten indien geen kamp!)</strong></p>
<hr/>
<div class="row">
	<div class="col-md-4 form-group">
		{!! Form::label('datum_voordag', 'Start voordag(en):') !!}
		@if (isset($event))
			{!! Form::input('date', 'datum_voordag', $event->datum_voordag->format('Y-m-d'), ['class' => 'form-control', 'placeholder' => 'Format: jjjj-mm-dd']) !!}
		@else
			{!! Form::input('date', 'datum_voordag', null, ['class' => 'form-control', 'placeholder' => 'Format: jjjj-mm-dd']) !!}
		@endif
	</div>
	
	<div class="col-md-4 form-group">
		{!! Form::label('prijs', 'Kampprijs (zonder korting):') !!}
		<div class="input-group">
			<span class="input-group-addon">€</span>
			{!! Form::input('number', 'prijs', null, ['class' => 'form-control', 'min' => 0]) !!}
		</div>
	</div>
	
	<div class="col-md-2 form-group">
		{!! Form::label('streeftal', 'Streeftal L / D:') !!}
		{!! Form::select('streeftal', $streeftal_options, null, ['class' => 'form-control']) !!}
	</div>
	
	<div class="col-md-2 form-group">
		{!! Form::label('vol', 'Kamp vol') !!}<br/>
		{!! Form::hidden('vol', 0) !!}
		{!! Form::checkbox('vol', 1, null, ['style' => 'margin-top:14px;']) !!}
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<p class="well">
			Is de kampprijs nog niet vastgesteld? Zet hem dan op het cijfer 0! De automatische mails geven dan door dat de ouder per mail bericht krijgt wanneer de prijs is vastgesteld.
		</p>
	</div>
</div>

<div class="form-group">
	{!! Form::label('beschrijving', 'Beschrijving (website):') !!}
	{!! Form::textarea('beschrijving', null, ['class' => 'form-control', 'rows' => '5']) !!}
</div>

<hr/>

<div class="form-group">
	{!! Form::label('opmerkingen', 'Opmerkingen:') !!}
	{!! Form::textarea('opmerkingen', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
	{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
</div>