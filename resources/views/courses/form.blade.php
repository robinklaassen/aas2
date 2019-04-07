<div class="row">
	<div class="col-sm-4 form-group">
		{!! Form::label('naam', 'Naam vak:') !!}
		{!! Form::text('naam', null, ['class' => 'form-control']) !!}
	</div>
	
	<div class="col-sm-2 form-group">
		{!! Form::label('code', 'Vakcode:') !!}
		{!! Form::text('code', null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-6 form-group">
		{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
	</div>
</div>