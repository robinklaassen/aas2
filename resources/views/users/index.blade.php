@extends('master')

@section('title')
Gebruikers
@endsection

@section('content')
<!-- Dit is de overzichtspagina voor gebruikers -->

<!-- Titelbalk met knoppen -->
<div class="row">
	<div class="col-sm-6">
		<h1>Gebruikers</h1>
	</div>
	<div class="col-sm-6">
		<p class="text-right">
			@can("createMember", \App\User::class)
			<a class="btn btn-primary" type="button" href="{{ url('users/create-for-member') }}"
				style="margin-top:21px;">Nieuwe gebruiker (lid)</a>
			@endcan
			@can("createParticipant", \App\User::class)
			<a class="btn btn-primary" type="button" href="{{ url('users/create-for-participant') }}"
				style="margin-top:21px;">Nieuwe gebruiker (deelnemer)</a>
			@endcan
		</p>
	</div>
</div>

<hr />

<div class="alert alert-danger alert-important" role="alert">
	Deze pagina komt binnenkort te vervallen. Een nieuw wachtwoord voor een lid of deelnemer kan geregeld worden op
	diens pagina, en de rollen van een lid kunnen worden ingesteld door bij het lid op 'Bewerken' te klikken.
</div>

<div role="tabpanel">

	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#leden" aria-controls="leden" role="tab"
				data-toggle="tab">Leden</a></li>
		<li role="presentation"><a href="#deelnemers" aria-controls="deelnemers" role="tab"
				data-toggle="tab">Deelnemers</a></li>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content" style="margin-top:20px;">
		<div role="tabpanel" class="tab-pane active" id="leden">
			<!-- Gebruikerstabel -->
			<table class="table table-hover" id="ledenTable">
				<thead>
					<tr>
						<th>Gebruikersnaam</th>
						<th>Eigenaar</th>
						<th>Admin</th>
						<th>Aangemaakt op</th>
						<th>Laatst ingelogd</th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</thead>

				<tbody>
					@foreach ($memberUsers as $user)
					<tr>
						<td>{{ $user->username }}</td>
						<td><a href="{{ url('/members', $user->profile->id) }}">{{ $user->profile->voornaam }}
								{{ $user->profile->tussenvoegsel }} {{ $user->profile->achternaam }}</a></td>
						<td>{{ ($user->is_admin) ? 'Ja' : 'Nee' }} @if ($user->is_admin == 2) + @endif</td>
						<td>{{ $user->created_at->toDateString() }}</td>
						<td>@if ($user->last_login) {{ $user->last_login->toDateString() }} @endif</td>
						<td>
							@can("changePassword", $user)
							<a href="{{ url('/users', [$user->id, 'password']) }}"><span
									class="glyphicon glyphicon-barcode" data-toggle="tooltip"
									title="Nieuw wachtwoord"></span></a>
							@endcan
						</td>
						<td>
							@if (\Auth::user()->is_admin == 2)
							<a href="{{ url('/users', [$user->id, 'admin']) }}"><span class="glyphicon glyphicon-king"
									data-toggle="tooltip" title="Admin-rechten wijzigen"></span></a>
							@endif
						</td>
						<td>
							@can("delete", $user)
							<a href="{{ url('/users', [$user->id, 'delete']) }}"><span
									class="glyphicon glyphicon-remove" data-toggle="tooltip"
									title="Verwijderen"></span></a>
							@endcan
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<div role="tabpanel" class="tab-pane" id="deelnemers">
			<!-- Gebruikerstabel -->
			<table class="table table-hover" id="deelnemersTable">
				<thead>
					<tr>
						<th>Gebruikersnaam</th>
						<th>Eigenaar</th>
						<th>Aangemaakt op</th>
						<th>Laatst ingelogd</th>
						<th></th>
						<th></th>
					</tr>
				</thead>

				<tbody>
					@foreach ($participantUsers as $user)
					<tr>
						<td>{{ $user->username }}</td>
						<td><a href="{{ url('/participants', $user->profile->id) }}">{{ $user->profile->voornaam }}
								{{ $user->profile->tussenvoegsel }} {{ $user->profile->achternaam }}</a></td>
						<td>{{ $user->created_at->toDateString() }}</td>
						<td>@if ($user->last_login) {{ $user->last_login->toDateString() }} @endif</td>
						<td>
							@can("changePassword", $user)
							<a href="{{ url('/users', [$user->id, 'password']) }}"><span
									class="glyphicon glyphicon-barcode" data-toggle="tooltip"
									title="Nieuw wachtwoord"></span></a>
							@endcan
						</td>
						<td>
							@can("delete", $user)
							<a href="{{ url('/users', [$user->id, 'delete']) }}"><span
									class="glyphicon glyphicon-remove" data-toggle="tooltip"
									title="Verwijderen"></span></a>
							@endcan
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

@endsection

@section ('footer')
<!-- These scripts load DataTables -->
<script type="text/javascript">
	$( document ).ready(function() {
    $('#ledenTable').DataTable({
		paging: false,
		order: [[ 0, "asc" ]],
		columns: [
			null,
			null,
			null,
			null,
			null,
			{'orderable':false},
			{'orderable':false},
			{'orderable':false}
		]
	});
	
	$('#deelnemersTable').DataTable({
		paging: false,
		order: [[ 0, "asc" ]],
		columns: [
			null,
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