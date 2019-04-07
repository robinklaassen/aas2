@extends('master')

@section('title')
	Declaratie bewerken
@endsection

@section('content')
<!-- Dit is het formulier voor het bewerken van een declaratie -->


<h1>Declaratie bewerken</h1>

<hr/>

@include ('errors.list')

{!! Form::model($declaration, ['method' => 'PATCH', 'url' => 'declarations/'.$declaration->id ]) !!}

	<div class="row">
		<div class="col-md-2 form-group">
			{!! Form::label('date', 'Datum:') !!}
				{!! Form::input('date', 'date', $declaration->date->format('Y-m-d'), ['class' => 'form-control', 'placeholder' => 'Format: jjjj-mm-dd']) !!}
		</div>
		
		<div class="col-md-2 form-group">
			{!! Form::label('filename', 'Bestand:') !!}
			{!! Form::select('filename', $all_files, null, ['class' => 'form-control']) !!}
		</div>
		
		<div class="col-md-5 form-group">
			{!! Form::label('description', 'Omschrijving:') !!}
			{!! Form::text('description', null, ['class' => 'form-control']) !!}
		</div>
		
		<div class="col-md-2">
			<div class="form-group">
				<label for="amount">Bedrag:</label>
				<div class="input-group">
					<span class="input-group-addon">&euro;</span>
					<input type="number" name="amount" value="{{$declaration->amount}}" min="0" step="0.01" class="form-control money" required>
				</div>
			</div>
		</div>
		
		<div class="col-md-1 form-group">
			{!! Form::label('gift', 'Gift:') !!}<br/>
			{!! Form::hidden('gift', 0) !!}
			{!! Form::checkbox('gift', 1, null, ['style' => 'margin-top: 14px;']) !!}
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 form-group">
			{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
		</div>
	</div>
	
{!! Form::close() !!}

@endsection