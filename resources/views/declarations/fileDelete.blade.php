@extends('master')

@section('title')
	Bestand verwijderen
@endsection

@section('content')
<!-- Dit is het formulier voor het verwijderen van een bestand -->


<h1>Bestand verwijderen</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'declarations/files/'.$filename, 'method' => 'DELETE']) !!}

	<p>Weet je zeker dat je het bestand &#39;{{ $filename }}&#39; wil verwijderen?</p>
	
	<div class="row">
		<div class="col-sm-6 form-group">
			{!! Form::submit('Verwijderen', ['class' => 'btn btn-danger form-control']) !!}
		</div>
	</div>
{!! Form::close() !!}

@endsection