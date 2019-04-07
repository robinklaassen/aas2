@extends('master')

@section('title')
	Nieuwe declaratie
@endsection

@section('header')
<style>
input {
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
	Voor iedere declaratie vul je (van links naar rechts) de <strong>datum</strong> van aankoop, een korte maar dekkende <strong>omschrijving</strong> en het <strong>bedrag</strong> in. Met het vinkje kun je aangeven dat de aankoop een <strong>gift</strong> is &mdash; je krijgt dan geen geld terug. Heb je meerdere declaraties op één bestand (bijvoorbeeld bij een OV-chipkaartoverzicht), dan kun je met de plus-knop nieuwe regels toevoegen.
</p>

@foreach ($filenames as $key => $filename)

<div class="row">
	<div class="col-md-2 text-center" style="word-wrap: break-word;">
		@unless ($filename === null)
			<a href="{{ asset('uploads/declarations/' . $member->id . '/' . $filename) }}" target="_blank">{{$filename}}</a>
		@else
			-geen bestand-
		@endunless
		<input type="hidden" name="filename-{{$key}}" value="{{$filename}}">
	</div>
	
	<div class="col-md-10">
		<div class="row">
			<div class="col-md-3">
				<input type="date" name="date-{{$key}}[]" class="form-control">
			</div>
			<div class="col-md-5">
				<input type="text" name="description-{{$key}}[]" class="form-control">
			</div>
			<div class="col-md-3">
				<div class="input-group">
					<span class="input-group-addon">&euro;</span>
					<input type="number" name="amount-{{$key}}[]" min="0" step="0.01" class="form-control money">
				</div>
			</div>
			<div class="col-md-1 text-center" style="margin-top: 11px;">
				<input type="checkbox" name="gift-{{$key}}[]" class="gift">
			</div>
		</div>
		
		@unless ($filename === null)
			<div class="row" id="row-add-{{$key}}">
				<div class="col-md-12 text-center" style="font-size: 140%;">
					<span class="glyphicon glyphicon-plus-sign" aria-hidden="true" id="btn-add-{{$key}}"></span>
				</div>
			</div>
		@endunless
		
	</div>
</div>

<hr/>

@endforeach

{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
	
{!! Form::close() !!}

@endsection

@section('footer')
<script type="text/javascript">
$( document ).ready(function(){
	// Insert new input row when plus sign is clicked
	$(".glyphicon-plus-sign").click(function(){
		var d = $(this).attr('id').split('-')[2];
		if (confirm("Weet je zeker dat je een regel voor dit bestand wil toevoegen?")) {
			$("#row-add-"+d).before(
				'<div class="row">'+
					'<div class="col-md-3">'+
						'<input type="date" name="date-'+d+'[]" class="form-control">'+
					'</div>'+
					'<div class="col-md-5">'+
						'<input type="text" name="description-'+d+'[]" class="form-control">'+
					'</div>'+
					'<div class="col-md-3">'+
						'<div class="input-group">'+
							'<span class="input-group-addon">&euro;</span>'+
							'<input type="number" name="amount-'+d+'[]" min="0" step="0.01" class="form-control money">'+
						'</div>'+
					'</div>'+
					'<div class="col-md-1 text-center" style="margin-top: 11px;">'+
						'<input type="checkbox" name="gift-'+d+'[]" class="gift" value="0" onclick="changeValueCheckbox(this)">'+
					'</div>'+
				'</div>'
			);
		}
	});
});
</script>
@endsection