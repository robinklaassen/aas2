@extends('master')

@section('title')
@if ($viewType == 'admin')
{{ $member->volnaam }}
@elseif ($viewType == 'profile')
Mijn profiel
@endif
@endsection

@section('content')
<!-- Dit is de pagina met gegevens voor een specifiek lid -->

<!-- Volledige naam en knoppen -->
<div class="row">
	<div class="col-sm-6">
		<h1>{{ $member->volnaam }}</h1>
	</div>
	<div class="col-sm-6">
		<p class="text-right">
			@if ($viewType == 'profile')

			@can("update", $member)
			<a class="btn btn-primary" type="button" href="{{ url('/profile/edit') }}" style="margin-top:21px;">Bewerken</a>
			@endcan
			@can("onEvent", $member)
			<a class="btn btn-info" type="button" href="{{ url('/profile/on-camp') }}" style="margin-top:21px;">Op kamp</a>
			@endcan
			@can("editFinance", $member)
			<a class="btn btn-success" type="button" href="{{ url('/profile/declare') }}" style="margin-top:21px;">Declaratie</a>
			@endcan
			@can("editPassword", $member)
			<a class="btn btn-warning" type="button" href="{{ url('/profile/password') }}" style="margin-top:21px;">Nieuw wachtwoord</a>
			@endcan
			
			@elseif ($viewType == 'admin')

			@can("update", $member)
			<a class="btn btn-primary" type="button" href="{{ url('/members', [$member->id, 'edit']) }}" style="margin-top:21px;">Bewerken</a>
			@endcan
			@can("onEvent", $member)
			<a class="btn btn-info" type="button" href="{{ url('/members', [$member->id, 'on-event']) }}" style="margin-top:21px;">Op evenement</a>
			@endcan
			@can("delete", $member)
			<a class="btn btn-danger" type="button" href="{{ url('/members', [$member->id, 'delete']) }}" style="margin-top:21px;">Verwijderen</a>
			@endcan
			
			@endif
		</p>
	</div>
</div>

<hr />

<!-- Geen KMG waarschuwing -->
@if (\Auth::user()->can("showAdministrative", $member) && $member->kmg == 0)
<div class="alert alert-dismissible alert-warning alert-important">
	<button type="button" class="close" data-dismiss="alert">x</button>
	<p><span class="glyphicon glyphicon-exclamation-sign"></span>&nbsp;&nbsp;Dit lid heeft nog geen KMG gehad!</p>
</div>
@endif

<div class="row">

	<!-- Linker kolom -->
	<div class="col-md-6">

		<!-- Profielfoto -->
		<div style="text-align:center; margin-bottom:10px;">
			@if ($viewType == 'profile')
			<a data-toggle="modal" data-target="#uploadPic" style="cursor:pointer;">
				@endif
				@if(file_exists(public_path().'/img/profile/full/'.$member->id.'.jpg'))
				<img src="/img/profile/full/{{$member->id}}.jpg" height="300" @if($viewType=='profile' )
					data-toggle="tooltip" title="Klik om te wijzigen" @endif />
				@else
				<img src="/img/profile/no-profile-{{ ($member->geslacht == 'M') ? 'm' : 'f' }}.gif" height="300"
					@if($viewType=='profile' ) data-toggle="tooltip" title="Klik om te wijzigen" @endif />
				@endif
				@if ($viewType == 'profile')
			</a>
			@endif
		</div>

		<!-- Profieltabel -->
		<table class="table table-hover">
			<caption>Profiel</caption>
			@can("showPrivate", $member)
			<tr>
				<td>Geboortedatum</td>
				<td>{{ $member->geboortedatum->format('d-m-Y') }}
					<small>({{ ($member->publish_birthday) ? 'publiek' : 'niet publiek' }}
						<a
							title="Gepubliceerde verjaardagen zijn te zien op de startpagina van AAS voor ingelogde leden en op de digitale Anderwijskalender">?</a>)
					</small>
				</td>
			</tr>
			<tr>
				<td>Geslacht</td>
				<td>{{ $member->geslacht }}</td>
			</tr>
			<tr>
				<td>Adres</td>
				<td>{{ $member->adres }}</td>
			</tr>
			<tr>
				<td>Postcode</td>
				<td>{{ $member->postcode }}</td>
			</tr>
			@endcan
			<tr>
				<td>Woonplaats</td>
				<td>{{ $member->plaats }}</td>
			</tr>
			@can("showPrivate", $member)
			<tr>
				<td>Telefoonnummer</td>
				<td>{{ $member->telefoon }}</td>
			</tr>
			<tr>
				<td>Emailadres persoonlijk</td>
				<td><a href="mailto:{{$member->email}}">{{ $member->email }}</a></td>
			</tr>
			@endcan
			@can("showFinancial", $member)
			<tr>
				<td>Rekeningnummer</td>
				<td>{{ $member->iban }}</td>
			</tr>
			@endcan
			<tr>
				<td>Rijbewijs?</td>
				<td>{{ $member->rijbewijs == '1' ? 'Ja' : 'Nee' }}</td>
			</tr>
			@can("showPrivate", $member)
			<tr>
				<td>Niveau eindexamen</td>
				<td>{{ $member->eindexamen }}</td>
			</tr>
			<tr>
				<td>Studie</td>
				<td>{{ $member->studie }}</td>
			</tr>
			<tr>
				<td>Afgestudeerd?</td>
				<td>{{ $member->afgestudeerd == '1' ? 'Ja' : 'Nee' }}</td>
			</tr>
			@endcan
			<tr>
				<td>Hoe bij Anderwijs</td>
				<td>{{ $member->hoebij }}</td>
			</tr>
			<tr>
				<td>Ranonkeltje</td>
				<td>{{ $member->ranonkeltje }}</td>
			</tr>
			@can("showPractical", $member)
			<tr>
				<td>Overige informatie</td>
				<td style="white-space:pre-wrap;">{{ $member->opmerkingen }}</td>
			</tr>
			@endcan
		</table>


	</div>

	<!-- Rechter kolom -->
	<div class="col-md-6">
		@can("showAdministrative", $member)
		<!-- Puntensysteem vak -->
		<div class="row">
			<div class="col-md-3">
				<h3 style="margin:0px;">Level {{$member->rank}}</h3>
			</div>
			<div class="col-md-9">
				<div class="progress" style="height: 2em;">
					<div class="progress-bar" role="progressbar"
						style="width: {{ round(($member->points - $ranks[$member->rank]) / ($ranks[$member->rank + 1] - $ranks[$member->rank]) * 100) }}%;">
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				{{$member->points}} punten
			</div>
			<div class="col-md-9 text-right">
				Nog {{ $ranks[$member->rank + 1] - $member->points }} punten tot level {{$member->rank + 1}}!
			</div>
		</div>
		<div class="row">
			<div class="col-md-7">
				<br />
				Meest recente actie: {{$member->mostrecentaction['name']}} ({{$member->mostrecentaction['points']}} pt)
			</div>
			<div class="col-md-5 text-right">
				<br />
				<a data-toggle="modal" data-target="#actions" style="cursor:pointer;">Lijst met acties</a>
			</div>
		</div>
		@endcan

		<hr />


		<!-- Administratietabel -->
		<table class="table table-hover">
			<caption>Administratie</caption>
			@can("showAdministrative", $member)
			<tr>
				<td>AAS ID</td>
				<td>{{ $member->id }}</td>
			</tr>
			<tr>
				<td>Soort lid</td>
				<td>{{ $member->soort }}</td>
			</tr>
			<tr>
				<td>Account(naam)</td>
				<td>@if($member->user()->first()) {{ $member->user()->first()->username }} @else -geen- @endif</td>
			</tr>
			@endcan
			<tr>
				<td>Emailadres bij Anderwijs</td>
				<td><a href="mailto:{{$member->email_anderwijs}}">{{ $member->email_anderwijs }}</a></td>
			</tr>
			@can("showAdministrative", $member)
			<tr>
				<td>Heeft VOG ingeleverd?</td>
				<td>{{ $member->vog == '1' ? 'Ja' : 'Nee' }}</td>
			</tr>
			<tr>
				<td>Automatische incasso</td>
				<td>{{ $member->incasso == '1' ? 'Ja' : 'Nee' }}</td>
			</tr>
			@endcan

			<tr>
				<td>Rollen</td>
				<td>
					@if($member->user()->first())
						@foreach ($member->user()->first()->roles as $role)
						<span class="label label-info">{{ $role->title }}</span>
						@endforeach
					@endif
				</td>
			</tr>
			@if(\Auth::user()->hasRole("aasbaas") || \App::environment("local"))
			<tr>
				<td>Rechten</td>
				<td>
					@foreach ($member->user()->first()->capabilities() as $capa)
					<span class="label label-default ">{{ $capa->name }}</span>
					@endforeach

				</td>
			</tr>
			@endif

			@can("showSpecial", $member)
			<tr>
				<td>Ervaren trainer</td>
				<td>{{ $member->ervaren_trainer == '1' ? 'Ja' : 'Nee' }}</td>
			</tr>
			@endcan
			@can("showBasic", $member)
			<tr>
				<td>Lid sinds</td>
				<td>{{ $member->created_at->format('d-m-Y') }}</td>
			</tr>
			@endcan
			@can("showAdministrative", $member)
			<tr>
				<td>Laatste update</td>
				<td>{{ $member->updated_at->format('d-m-Y') }}</td>
			</tr>
			@endcan
		</table>

		@can("showPractical", $member)
		<p><a data-toggle="modal" data-target="#fellows" style="cursor:pointer;">Met wie ben ik allemaal op kamp geweest?</a></p>
		@endcan

		@can("showPractical", $member)
		<!-- Vakken van dit lid -->
		<table class="table table-hover">
			<caption>
				<div style="clear:both;">
					<div style="float:left;">Vakken</div>
					@can("editPractical", $member)
					<div style="float:right;">
						<a href="{{ $viewType == 'admin' ? url('/members', [$member->id, 'add-course']) : url('/profile', ['add-course'])  }}">
							<span class="glyphicon glyphicon-plus" aria-hidden="true" data-toggle="tooltip" title="Vak toevoegen"></span>
						</a>
						@endcan
					</div>
				</div>
			</caption>
			@forelse ($member->courses()->orderBy('naam')->get() as $course)
			<tr>

				<td> 
					@can("view", $course)
					<a href="{{ url('/courses', $course->id) }}">  {{ $course->naam }} </a>
					@else
					{{ $course->naam }}
					@endcan
				</td>
				<td>{{ $course->pivot->klas }}</td>
				<td>
					@can("editPractical", $member)
					@if ($viewType == 'admin')
					<a href="{{ url('/members', [$member->id, 'edit-course', $course->id]) }}">
						<span class="glyphicon glyphicon-edit" aria-hidden="true" data-toggle="tooltip" title="Vak bewerken"></span>
					</a>
					@elseif ($viewType == 'profile')
					<a href="{{ url('/profile', ['edit-course', $course->id]) }}">
						<span class="glyphicon glyphicon-edit" aria-hidden="true" data-toggle="tooltip" title="Vak bewerken"></span>
					</a>
					@endif
					@endcan
				</td>
				<td>
					@can("editPractical", $member)
					@if ($viewType == 'admin')
					<a href="{{ url('/members', [$member->id, 'remove-course', $course->id]) }}">
						<span class="glyphicon glyphicon-remove" aria-hidden="true" data-toggle="tooltip" title="Vak verwijderen"></span>
					</a>
					@elseif ($viewType == 'profile')
						<a href="{{ url('/profile', ['remove-course', $course->id]) }}">
							<span class="glyphicon glyphicon-remove" aria-hidden="true" data-toggle="tooltip" title="Vak verwijderen"></span>
						</a>
					@endif
					@endcan
				</td>
			</tr>
			@empty
			<tr>
				<td>Geen vakken gevonden</td>
			</tr>
			@endforelse
		</table>
		@endcan
		
	</div>

</div>

@can("viewAny", \App\Comment::class)
	@include('partials.comments', [ 'comments' => $member->comments, 'type' => 'App\Member', 'key' => $member->id ])
@endif

<hr />

<div class="row">
	<!-- Linker kolom -->
	<div class="col-sm-4">
		<!-- Kampen van dit lid -->
		<table class="table table-hover">
			<caption>Kampen ({{ $member->events()->where('type','kamp')->count() }})</caption>
			@forelse ($member->events()->where('type','kamp')->orderBy('datum_start', 'desc')->get() as $event)
			<tr>
				<td>
					@can("view", $event)
					<a href="{{ url('/events', $event->id) }}">{{ $event->naam }}</a>
					@else
					{{ $event->naam }}
					@endcan

				</td>
				<td>
					@if ($event->pivot->wissel)
					<span class="glyphicon glyphicon-hourglass" aria-hidden="true" data-toggle="tooltip"
						title="Wisselleiding"></span>
					@endif
				</td>
				<td>
					@if (($viewType == 'profile') && ($event->reviews->count() > 0))
					<a href="{{ url('/profile/reviews', $event->id) }}">
						<span class="glyphicon glyphicon-dashboard" aria-hidden="true" data-toggle="tooltip"
							title="Enquetes bekijken"></span>
					</a>
					@endif
				</td>
				<td>
					@can("showAdvanced", $event)
					{{ $event->code }}
					@else
					{{ $event->datum_start->format('d-m-Y') }}
					@endcan
				</td>
			</tr>
			@empty
			<tr>
				<td>Geen kampen gevonden</td>
			</tr>
			@endforelse
		</table>
	</div>
	<!-- Middelste kolom -->
	<div class="col-sm-4">
		<!-- Trainingen door dit lid -->
		<table class="table table-hover">
			<caption>Trainingen ({{ $member->events()->where('type','training')->count() }})</caption>
			@forelse ($member->events()->where('type','training')->orderBy('datum_start', 'desc')->get() as $event)
			<tr>
				<td>
					@can("view", $event)
					<a href="{{ url('/events', $event->id) }}">{{ $event->naam }}</a>
					@else
					{{ $event->naam }}
					@endcan
				</td>
				<td>
					@can("showAdvanced", $event)					
					{{ $event->code }}
					@else
					{{ $event->datum_start->format('d-m-Y') }}
					@endcan
				</td>
			</tr>
			@empty
			<tr>
				<td>Geen trainingen gevonden</td>
			</tr>
			@endforelse
		</table>
	</div>
	<!-- Rechter kolom -->
	<div class="col-sm-4">
		<!-- Overige evenementen -->
		<table class="table table-hover">
			<caption>Overige evenementen ({{ $member->events()->where('type','overig')->count() }})</caption>
			@forelse ($member->events()->where('type','overig')->orderBy('datum_start', 'desc')->get() as $event)
			<tr>
				<td>
					@can("view", $event)
					<a href="{{ url('/events', $event->id) }}">{{ $event->naam }}</a>
					@else
					{{ $event->naam }}
					@endcan
				</td>
				<td>
					@can("showAdvanced", $event)					
					{{ $event->code }}
					@else
					{{ $event->datum_start->format('d-m-Y') }}
					@endcan
				</td>
			</tr>
			@empty
			<tr>
				<td>Geen overige evenementen gevonden</td>
			</tr>
			@endforelse
		</table>
	</div>
</div>

@can("showAdministrative", $member)
<!-- Modal: punten -->
<div class="modal fade" id="actions" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Lijst met acties</h4>
			</div>
			<div class="modal-body">
				<table class="table">
					<thead>
						<tr>
							<th>Datum</th>
							<th>Actie</th>
							<th>Punten</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($member->listofactions as $item)
						<tr>
							<td>{{$item['date']->format('d-m-Y')}}</td>
							<td>{{$item['name']}}</td>
							<td>{{$item['points']}}</td>
						</tr>
						@endforeach
						@if ($member->hasstraightflush)
						<tr>
							<td></td>
							<td>Straight flush</td>
							<td>3</td>
						</tr>
						@endif
						<tr>
							<td></td>
							<td><strong>Totaal:</strong></td>
							<td><strong>{{$member->points}}</strong></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Ja, bedankt</button>
			</div>
		</div>
	</div>
</div>
@endcan

@can("showPractical", $member)
<!-- Modal: met wie op kamp geweest -->
<div class="modal fade" id="fellows" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Ik ben op kamp geweest met:</h4>
			</div>
			<div class="modal-body">
				@forelse ($member->fellows as $x)
				{{ $x->volnaam }}<br />
				@empty
				Nog helemaal niemand! :(
				@endforelse
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Top!</button>
			</div>
		</div>
	</div>
</div>
@endcan

@if ($viewType == 'profile')
<!-- Modal: foto uploaden -->
<div class="modal fade" id="uploadPic" tabindex="-1" role="dialog" aria-labelledby="uploadPicLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="uploadPicLabel">Nieuwe foto uploaden</h4>
			</div>
			{!! Form::open(['url' => 'profile', 'files' => true]) !!}
			<div class="modal-body">
				<p>Let op: werkt op dit moment alleen nog met .jpg bestanden!</p>
				<input type="file" id="photo" name="photo" />
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">En door</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Terug</button>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endif

@endsection