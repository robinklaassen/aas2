@extends('master')

@section('title')
	Declaraties verwerken
@endsection

@section('content')
<!-- Dit is het formulier voor het verwerken van declaraties -->


<h1>Declaraties verwerken</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'declarations/process/'.$member->id, 'method' => 'POST']) !!}

	<p>Je staat op het punt om alle openstaande declaraties van <strong>{{$member->volnaam}}</strong> te verwerken.</p>
	
	<p>Download eerst de betreffende bestanden, inclusief overzicht, middels de onderstaande knop.</p>
	
	<div class="row">
		<div class="col-sm-6 form-group">
			<a class="btn btn-info" href="{{ 'https://aas2.anderwijs.nl/' . $zipname }}">Download .zip file</a>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6 form-group">
			{!! Form::submit('Verwerken', ['class' => 'btn btn-primary form-control']) !!}
		</div>
	</div>
{!! Form::close() !!}

@endsection