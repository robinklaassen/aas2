@extends('master')

@section('title')
	Declaratie verwijderen
@endsection

@section('content')
<!-- Dit is het formulier voor het verwijderen van een declaratie -->


<h1>Declaratie verwijderen</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'declarations/'.$declaration->id, 'method' => 'DELETE']) !!}

	<p>Weet je zeker dat je de declaratie &#39;{{ $declaration->description }}&#39; van {{ $declaration->date->format('d-m-Y') }} wil verwijderen?</p>
	
	<div class="row">
		<div class="col-sm-6 form-group">
			{!! Form::submit('Verwijderen', ['class' => 'btn btn-danger form-control']) !!}
		</div>
	</div>
{!! Form::close() !!}

@endsection