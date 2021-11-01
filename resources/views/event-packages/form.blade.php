
<div class="row">

	<div class="col-sm-6 form-group">
		{!! Form::label('code', 'Code:') !!}
		{!! Form::text('code', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-6 form-group">
		{!! Form::label('type', 'Type:') !!}
		{!! Form::select('type', (\App\Models\EventPackage::class)::TYPE_DESCRIPTIONS, null, ['class' => 'form-control']) !!}
	</div>
</div>
<div class="row">
	<div class="col-sm-6 form-group">
		{!! Form::label('title', 'Titel:') !!}
		{!! Form::text('title', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-6 form-group">
		{!! Form::label('price', 'Prijs:') !!}
		<div class="input-group">
			<span class="input-group-addon">€</span>
			{!! Form::input('number','price', null, ['class' => 'form-control', "min" => 0]) !!}
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12 form-group">
		{!! Form::label('description', 'Omschrijving:') !!}
		{!! Form::textarea('description', null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-5 form-group">
		{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
	</div>
</div>
