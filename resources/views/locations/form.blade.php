<div class="row">
	<div class="col-sm-12 form-group">
		{!! Form::label('naam', 'Naam locatie:') !!}
		{!! Form::text('naam', null, ['class' => 'form-control']) !!}
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
		{!! Form::label('plaats', 'Plaats:') !!}
		{!! Form::text('plaats', null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="row">
	@canany("editAdvanced", \App\Location::class, $location)
	<div class="col-sm-5 form-group">
		{!! Form::label('beheerder', 'Beheerder:') !!}
		{!! Form::text('beheerder', null, ['class' => 'form-control']) !!}
	</div>
	@endcanany

	<div class="col-sm-7 form-group">
		{!! Form::label('website', 'Website:') !!}
		{!! Form::text('website', null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-5 form-group">
		{!! Form::label('telefoon', 'Telefoonnummer:') !!}
		{!! Form::text('telefoon', null, ['class' => 'form-control']) !!}
	</div>


	@canany("editAdvanced", \App\Location::class, $location)
	<div class="col-sm-7 form-group">
		{!! Form::label('email', 'Emailadres:') !!}
		{!! Form::email('email', null, ['class' => 'form-control']) !!}
	</div>
	@endcanany
</div>

@canany("editAdvanced", \App\Location::class, $location)
<div class="form-group">
	{!! Form::label('prijsinfo', 'Prijsinformatie:') !!}
	{!! Form::textarea('prijsinfo', null, ['class' => 'form-control']) !!}
</div>
@endcanany

<div class="form-group">
	{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
</div>