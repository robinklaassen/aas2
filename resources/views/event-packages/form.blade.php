
<div class="row">
	
	<div class="col-sm-6 form-group">
		{!! Form::label('code', 'Code:') !!}
		{!! Form::input('code', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-6 form-group">
		{!! Form::label('type', 'Type:') !!}
		{!! Form::input('type', (\App\EventPackage::class)::TYPE_DESCRIPTIONS, null, ['class' => 'form-control']) !!}
	</div>
</div>
<div class="row">
	<div class="col-sm-6 form-group">
		{!! Form::label('title', 'Titel:') !!}
		{!! Form::input('title', null, ['class' => 'form-control']) !!}
	</div>

	<div class="col-sm-6 form-group">
		{!! Form::label('price', 'Prijs:') !!}
		<div class="input-group">
			<span class="input-group-addon">€</span>
			{!! Form::input('price', null, ['class' => 'form-control', "min" => 0]) !!}
		</div>>
	</div>
</div>

<div class="row">
	<div class="col-sm-12 form-group">
		{!! Form::label('titel', 'Titel:') !!}
		{!! Form::input('title', null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-5 form-group">
		{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
	</div>
</div>