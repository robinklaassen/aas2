@extends('master')

@section('title')
	Nieuwe declaratie
@endsection

@section('header')
<style>
input, select, #row-add {
	margin-bottom: 10px;
}
</style>
@endsection

@section('content')
<!-- Dit is het formulier voor het afmaken van een nieuwe declaratie -->


<h1>Nieuwe declaratie - stap 2</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'declarations']) !!}

<p class="well">
	Voor iedere declaratie kies je het betreffende <strong>bestand</strong> en vul je in: de <strong>datum</strong> van aankoop, een korte maar dekkende <strong>omschrijving</strong> en het <strong>bedrag</strong>. Met het vinkje kun je aangeven dat de aankoop een <strong>gift</strong> is &mdash; je krijgt dan geen geld terug. Met de plus-knop onderaan voeg je een nieuwe regel toe. Zo kun je ook meerdere declaraties op hetzelfde bestand zetten!
</p>

@foreach ($uploaded_files as $i => $filename)

<div class="row">

	<div class="col-md-2">
		<select name="filename[]" class="form-control">
			<option value="">-</option>
			@foreach ($all_files as $key => $value)
			<option value="{{$key}}" @if ($value == $filename) selected @endif>{{$value}}</option>
			@endforeach
		</select>
	</div>
	
	<div class="col-md-2">
		<input type="date" name="date[]" class="form-control" required>
	</div>
	
	<div class="col-md-5">
		<input type="text" name="description[]" class="form-control" required>
	</div>
	
	<div class="col-md-2">
		<div class="input-group">
			<span class="input-group-addon">&euro;</span>
			<input type="number" name="amount[]" min="0" step="0.01" class="form-control money" required>
		</div>
	</div>
	
	<div class="col-md-1 text-center" style="margin-top: 11px;">
		<input type="hidden" name="gift[{{$i}}]" value="0">
		<input type="checkbox" name="gift[{{$i}}]" value="1" class="gift">
	</div>
	
</div>

@endforeach

<div class="row" id="row-add">
	<div class="col-md-12 text-center" style="font-size: 140%;">
		<span class="glyphicon glyphicon-plus-sign" aria-hidden="true" id="btn-add"></span>
	</div>
</div>

{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
	
{!! Form::close() !!}

@endsection

@section('footer')
<script type="text/javascript">
$( document ).ready(function(){
	// Define starting key for adding rows
	var i = <?php echo count($uploaded_files); ?>;
	
	// Insert new input row when plus sign is clicked
	$("#btn-add").click(function(){
		if (confirm("Weet je zeker dat je een regel voor dit bestand wil toevoegen? Je moet die dan invullen!")) {
			$("#row-add").before(
				'<div class="row">'+
					'<div class="col-md-2">'+
						'<select name="filename[]" class="form-control">'+
							'<option value="">-</option>'+
							@foreach ($all_files as $key => $value)
							'<option value="{{$key}}">{{$value}}</option>'+
							@endforeach
						'</select>'+
					'</div>'+
					'<div class="col-md-2">'+
						'<input type="date" name="date[]" class="form-control" required>'+
					'</div>'+
					'<div class="col-md-5">'+
						'<input type="text" name="description[]" class="form-control" required>'+
					'</div>'+
					'<div class="col-md-2">'+
						'<div class="input-group">'+
							'<span class="input-group-addon">&euro;</span>'+
							'<input type="number" name="amount[]" min="0" step="0.01" class="form-control money" required>'+
						'</div>'+
					'</div>'+
					'<div class="col-md-1 text-center" style="margin-top: 11px;">'+
						'<input type="hidden" value="0" name="gift['+i+']">'+
						'<input type="checkbox" value="1" name="gift['+i+']" class="gift">'+
					'</div>'+
				'</div>'
			);
			i++;
		}
	});
});
</script>
@endsection