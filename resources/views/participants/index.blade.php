@extends('master')

@section('title')
	Deelnemers
@endsection

@section('content')
<!-- Dit is de overzichtspagina voor deelnemers -->

<!-- Titelbalk met knoppen -->
<div class="row">
	<div class="col-sm-6">
		<h1>Deelnemers</h1>
	</div>
	<div class="col-sm-6">
		<p class="text-right">
			@can("create", \App\Participant::class)
			<a class="btn btn-primary" type="button" href="{{ url('participants/create') }}" style="margin-top:21px;">Nieuwe deelnemer</a>
			@endcan
			
			<a class="btn btn-success" type="button" href="{{ url('participants/export') }}" style="margin-top:21px;">Exporteren</a>
		</p>
	</div>
</div>

<hr/>

<!-- Tabel deelnemers -->
<table class="table table-hover" id="participantsTable" data-page-length="25">
	<thead>
		<tr>
			<th>Voornaam</th>
			<th></th>
			<th>Achternaam</th>
			<th>Woonplaats</th>
			<th>Geboortedatum</th>
			@can("showPrivateAny",  \App\Participant::class)			
			<th>Email ouder</th>
			<th>Email deelnemer</th>
			@endcan
		</tr>
	</thead>
	
	<tbody>
		@foreach ($participants as $participant)
			<tr>
				<td><a href="{{ url('/participants', $participant->id) }}">{{ $participant->voornaam }}</a></td>
				<td>{{ $participant->tussenvoegsel }}</td>
				<td>{{ $participant->achternaam }}</td>
				<td>{{ $participant->plaats }}</td>
				<td>{{ $participant->geboortedatum->format('Y-m-d') }}</td>
				@can("showPrivateAny",  \App\Participant::class)
				<td><a href="mailto:{{ $participant->email_ouder }}">{{ $participant->email_ouder }}</a></td>
				<td><a href="mailto:{{ $participant->email_deelnemer }}">{{ $participant->email_deelnemer }}</a></td>
				@endcan
			</tr>
		@endforeach
	</tbody>
</table>
	

@endsection

@section('footer')
<!-- These scripts load DataTables -->
<script type="text/javascript">
$( document ).ready(function() {
    $('#participantsTable').DataTable({
		order: [[ 0, "asc" ]],
		columns: [
			null,
			{'orderable':false},
			null,
			null,
			null,
			{'orderable':false},
			{'orderable':false}
		]
	});
});
</script>
@endsection