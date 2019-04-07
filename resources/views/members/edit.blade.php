@extends('master')

@section('title')
	@if ($viewType == 'admin')
		Lid bewerken
	@elseif ($viewType == 'profile')
		Mijn gegevens bewerken
	@endif
@endsection


@section('content')
<!-- Dit is het formulier voor het bewerken van een lid -->

@if ($viewType == 'admin')
	<h1>Lid bewerken</h1>
@elseif ($viewType == 'profile')
	<h1>Mijn gegevens bewerken</h1>
@endif

<hr/>

@include ('errors.list')

@if ($viewType == 'admin')
	{!! Form::model($member, ['method' => 'PATCH', 'url' => 'members/'.$member->id ]) !!}
@elseif ($viewType == 'profile')
	{!! Form::model($member, ['method' => 'PATCH', 'url' => 'profile' ]) !!}
@endif

	@include ('members.form')
	
{!! Form::close() !!}

@endsection