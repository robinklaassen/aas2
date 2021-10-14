@extends('master')

@section('title')
Leden
@endsection

@section('content')
<!-- Dit is de overzichtspagina voor leden -->

<!-- Titelbalk met knoppen -->
<div class="row">
	<div class="col-sm-6">
		<h1>Leden</h1>
	</div>
	<div class="col-sm-6">
		<p class="text-right">
			@can("create", \App\Member::class)
			<a class="btn btn-primary" type="button" href="{{ url('members/create') }}" style="margin-top:21px;">Nieuw
				lid</a>
			@endcan
			@can("showPracticalAny", \App\Member::class)
			<a class="btn btn-info" type="button" href="{{ url('members/search')}}" style="margin-top:21px;">Zoeken op
				vakdekking</a>
			@endcan
			@can("viewAny", \App\Member::class)
			<a class="btn btn-info" type="button" href="{{ url('members/search-skills')}}" style="margin-top:21px;">Zoeken op
				vaardigheden</a>
			<a class="btn btn-warning" type="button" href="{{ url('members/map') }}"
			style="margin-top:21px;">Kaart</a>
			@endcan
			@can("showAdministrativeAny", \App\Member::class)
			<a class="btn btn-success" type="button" href="{{ url('members/export') }}"
				style="margin-top:21px;">Exporteren</a>
			@endcan
		</p>
	</div>
</div>

<hr />

<div role="tabpanel">

	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#huidig" aria-controls="huidig" role="tab"
				data-toggle="tab">Huidige leden</a></li>
		@can("listOldMembers", \App\Member::class)
		<li role="presentation"><a href="#oud" aria-controls="oud" role="tab" data-toggle="tab">Oud-leden</a></li>
		@endcan
	</ul>

	<!-- Tab panes -->
	<div class="tab-content" style="margin-top:20px;">
		<div role="tabpanel" class="tab-pane active" id="huidig">
			<!-- Tabel huidige leden -->
			<table class="table table-hover" id="currentMembersTable">
				<thead>
					<tr>
						<th data-orderable>Voornaam</th>
						<th></th>
						<th data-orderable>Achternaam</th>
						<th data-orderable>Woonplaats</th>
						@can("showPracticalAny", \App\Member::class)
						<th data-orderable>Soort lid</th>
						@endcan
						@can("showAdministrativeAny", \App\Member::class)
						<th>VOG</th>
						@endcan
						@can("showPrivateAny", \App\Member::class)
						<th>Telefoon</th>
						@endcan
						<th>Email</th>
						<th>Rol(len)</th>
					</tr>
				</thead>

				<tbody>
					@foreach ($current_members as $member)
					<tr>
						<td>
							@can("view", $member)
							<a href="{{ url('/members', $member->id) }}">{{ $member->voornaam }}</a>
							@else
							{{ $member->voornaam }}
							@endcan
						</td>
						<td>{{ $member->tussenvoegsel }}</td>
						<td>{{ $member->achternaam }}</td>
						<td>{{ $member->plaats }}</td>

						@can("showPracticalAny", \App\Member::class)
						<td>{{ $member->soort }}</td>
						@endcan

						@can("showAdministrativeAny", \App\Member::class)
						<td>
							@if ($member->vog)
							<span style="display:none;">1</span>
							<span class="glyphicon glyphicon-ok"></span>
							@else
							<span style="display:none;">0</span>
							<span class="glyphicon glyphicon-remove"></span>
							@endif
						</td>
						@endcan
						@can("showPrivateAny", \App\Member::class)
						<td>{{ $member->telefoon }}</td>
						@endcan
						<td><a href="mailto:{{ $member->email_anderwijs }}">{{ $member->email_anderwijs }}</a></td>
						<td>
							@if ($member->user !== null)
							@foreach ($member->user->public_roles as $role)
							<span class="label label-info">{{ $role->title }}</span>
							@endforeach
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		@can("listOldMembers", \App\Member::class)
		<div role="tabpanel" class="tab-pane" id="oud">
			<!-- Tabel oud-leden -->
			<table class="table table-hover" id="oldMembersTable" data-page-length="25">
				<thead>
					<tr>
						<th data-orderable>Voornaam</th>
						<th></th>
						<th data-orderable>Achternaam</th>
						<th data-orderable>Woonplaats</th>
						<th>Telefoon</th>
						<th>Email</th>
					</tr>
				</thead>

				<tbody>
					@foreach ($former_members as $member)
					<tr>
						<td><a href="{{ url('/members', $member->id) }}">{{ $member->voornaam }}</a></td>
						<td>{{ $member->tussenvoegsel }}</td>
						<td>{{ $member->achternaam }}</td>
						<td>{{ $member->plaats }}</td>
						<td>{{ $member->telefoon }}</td>
						<td><a href="mailto:{{ $member->email }}">{{ $member->email }}</a></td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@endcan
	</div>

</div>
@endsection

@section('footer')
<!-- These scripts load DataTables -->
<script type="text/javascript">
	
	$( document ).ready(function() {
		makeTableSortable("#currentMembersTable");
		makeTableSortable("#oldMembersTable");
	});
</script>
@endsection