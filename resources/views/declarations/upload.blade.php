@extends('master')

@section('title')
	Nieuwe declaratie
@endsection

@section('content')
<!-- Dit is het formulier voor het voorbereiden van een nieuwe declaratie -->

<h1>Nieuwe declaratie - stap 1</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => ['declarations', 'create'], 'files' => true]) !!}

<div class="row">
	<div class="col-sm-8">
		<p class="well">
			Upload hier je bestanden om te declareren. Dat kan met meerdere bestanden tegelijk! Om iets te declareren zonder een bestand, klik je gewoon op 'doorgaan'.
		</p>
	</div>
</div>

<div class="row">
	<div class="col-sm-8 form-group">
		{!! Form::label('files', 'Bestand(en):') !!}
		{!! Form::file('files[]', ['multiple' => true]) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-8 form-group">
		{!! Form::submit('Doorgaan', ['class' => 'btn btn-primary form-control']) !!}
	</div>
</div>
	
{!! Form::close() !!}

@endsection

@section('footer')
<script src="../js/bootstrap-prettyfile.js"></script>
<script type="text/javascript">
$( document ).ready(function() {
	$( 'input[type="file"]' ).prettyFile();
});
</script>
@endsection