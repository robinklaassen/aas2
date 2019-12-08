<h3>Profiel</h3>

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
		@if (isset($member))
		{!! Form::input('date', 'geboortedatum', $member->geboortedatum->format('Y-m-d'), ['class' => 'form-control',
		'placeholder' => 'Format: jjjj-mm-dd']) !!}
		@else
		{!! Form::input('date', 'geboortedatum', null, ['class' => 'form-control', 'placeholder' => 'Format:
		jjjj-mm-dd']) !!}
		@endif
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::label('publish_birthday', 'Publiceer verjaardag?',
		["title" => "Gepubliceerde verjaardagen zijn te zien op de startpagina van AAS voor ingelogde leden" .
		" en op de digitale Anderwijskalender"]) !!}<br />
		{!! Form::hidden('publish_birthday', 0) !!}
		{!! Form::checkbox('publish_birthday', 1, null, ['style' => 'margin-top:14px;']) !!}
	</div>

	<div class="col-sm-5 form-group">
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
		{!! Form::text('postcode', null, ['class' => 'form-control', 'placeholder' => 'Format: 0000 AA']) !!}
	</div>

	<div class="col-sm-5 form-group">
		{!! Form::label('plaats', 'Woonplaats:') !!}
		{!! Form::text('plaats', null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-5 form-group">
		{!! Form::label('telefoon', 'Telefoonnummer:') !!}
		{!! Form::text('telefoon', null, ['class' => 'form-control', 'maxlength' => 10, 'placeholder' => '10 cijfers'])
		!!}
	</div>

	<div class="col-sm-7 form-group">
		{!! Form::label('email', 'Emailadres:') !!}
		{!! Form::email('email', null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-5 form-group">
		{!! Form::label('iban', 'Rekeningnummer (IBAN):') !!}
		{!! Form::text('iban', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::label('rijbewijs', 'Rijbewijs?') !!}<br />
		{!! Form::hidden('rijbewijs', 0) !!}
		{!! Form::checkbox('rijbewijs', 1, null, ['style' => 'margin-top:14px;']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-2 form-group">
		{!! Form::label('eindexamen', 'Niveau eindexamen:') !!}
		{!! Form::select('eindexamen', ['VMBO' => 'VMBO', 'HAVO' => 'HAVO', 'VWO' => 'VWO'], null, ['class' =>
		'form-control']) !!}
	</div>

	<div class="col-sm-3 form-group">
		{!! Form::label('studie', 'Studie:') !!}
		{!! Form::text('studie', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::label('afgestudeerd', 'Afgestudeerd?') !!}<br />
		{!! Form::hidden('afgestudeerd', 0) !!}
		{!! Form::checkbox('afgestudeerd', 1, null, ['style' => 'margin-top:14px;']) !!}
	</div>

	<div class="col-sm-3 form-group">
		{!! Form::label('hoebij', 'Hoe bij Anderwijs?') !!}
		{!! Form::text('hoebij', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::label('ranonkeltje', 'Ranonkeltje:') !!}
		{!! Form::select('ranonkeltje', ['geen' => 'geen', 'digitaal' => 'digitaal', 'papier' => 'papier', 'beide' =>
		'beide'], null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('opmerkingen', 'Overige informatie:') !!}
	{!! Form::textarea('opmerkingen', null, ['class' => 'form-control']) !!}
</div>

@if ($viewType == 'admin')

<h3>Administratie</h3>

<div class="row">
	<div class="col-sm-3 form-group">
		{!! Form::label('soort', 'Soort lid:') !!}
		{!! Form::select('soort', ['normaal' => 'normaal', 'aspirant' => 'aspirant', 'info' => 'info', 'oud' => 'oud'],
		null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::label('kmg', 'KMG gehad?') !!}<br />
		{!! Form::hidden('kmg', 0) !!}
		{!! Form::checkbox('kmg', 1, null, ['style' => 'margin-top:14px;']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::label('vog', 'VOG ingeleverd?') !!}<br />
		{!! Form::hidden('vog', 0) !!}
		{!! Form::checkbox('vog', 1, null, ['style' => 'margin-top:14px;']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-3 form-group">
		{!! Form::label('email_anderwijs', 'Emailadres bij Anderwijs:') !!}
		{!! Form::email('email_anderwijs', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::label('ervaren_trainer', 'Ervaren trainer?') !!}<br />
		{!! Form::hidden('ervaren_trainer', 0) !!}
		{!! Form::checkbox('ervaren_trainer', 1, null, ['style' => 'margin-top:14px;']) !!}
	</div>

	<div class="col-sm-2 form-group">
		{!! Form::label('incasso', 'Automatische incasso?') !!}<br />
		{!! Form::hidden('incasso', 0) !!}
		{!! Form::checkbox('incasso', 1, null, ['style' => 'margin-top:14px;']) !!}
	</div>
</div>

<!-- TODO: check on the aas-baas role !-->
<div class="row">
<div class="form-group">
	<div class="col-sm-3">
		{!! Form::label('roles', 'Rollen:') !!}
	</div>
	<div class="col-sm-9">
	@foreach (App\Role::all() as $role)
		<div class="form-check">
			<label class="form-check-label">
			{!! Form::checkbox('roles[]', $role->id, isset($member) ? $member->hasRole($role->title) : false, ['class' => 'form-check-input']) !!}
			{{ $role->title }}
			@if (\Auth::user()->hasRole("admin+"))  ({{ $role->tag }}) @endif
			</label>
		</div>
	@endforeach
	</div>
</div>
</div>
<div class="form-group">
	{!! Form::label('opmerkingen_admin', 'Opmerkingen:') !!}
	{!! Form::textarea('opmerkingen_admin', null, ['class' => 'form-control']) !!}
</div>

@if (\Auth::user()->is_admin == 2)
	<div class="form-group">
		{!! Form::label('opmerkingen_geheim', 'Supergeheime opmerkingen:') !!}
		{!! Form::textarea('opmerkingen_geheim', null, ['class' => 'form-control']) !!}
	</div>
@endif


@endif

<div class="form-group">
	{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
</div>