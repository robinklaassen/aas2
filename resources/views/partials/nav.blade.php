<!-- Navigation bar -->
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle Navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="{{ url('/home') }}">AAS 2.0</a>
				</div>

				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">

						@can("viewAny", \App\Models\Participant::class)
						<li class="{{ substr(Request::path(),0,12) == 'participants' ? 'active' : ''}}"><a href="{{ url('/participants') }}">Deelnemers</a></li>
						@endcan
						@can("viewAny", \App\Models\Member::class)
						<li class="{{ substr(Request::path(),0,7) == 'members' ? 'active' : ''}}"><a href="{{ url('/members') }}">Leden</a></li>
						@endcan
						@can("viewAny", \App\Models\Event::class)
						<li class="{{ substr(Request::path(),0,6) == 'events' ? 'active' : ''}}"><a href="{{ url('/events') }}">Evenementen</a></li>
						@endcan

						@can("viewOwn", \App\Models\Declaration::class)
						<li class="{{ substr(Request::path(),0,5) == 'declaration' ? 'active' : ''}}">
							<a href="{{ url('/declarations') }}">Declaraties</a>
						</li>
						@endcan

						@role("member")
						<li class="dropdown" >
							<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Overig <span class="caret"></span></a>
							<ul class="dropdown-menu">

								@can("viewAny", \App\Models\Location::class)
									<li class="{{ substr(Request::path(),0,9) == 'locations' ? 'active' : ''}}"><a href="{{ url('/locations') }}">Locaties</a></li>
								@endcan

								@role(["board"])
								<li class="{{ substr(Request::path(),0,5) == 'event-packages' ? 'active' : ''}}">
									<a href="{{ url('/event-packages') }}">Pakketten</a>
								</li>
								@endrole

								<li class="{{ substr(Request::path(),0,6) == 'graphs' ? 'active' : ''}}"><a href="{{ url('/graphs') }}">Grafieken</a></li>

								<li class="{{ substr(Request::path(),0,5) == 'lists' ? 'active' : ''}}"><a href="{{ url('/lists') }}">Lijsten</a></li>

								@can("viewAny", \App\Models\Course::class)
									<li class="{{ substr(Request::path(),0,7) == 'courses' ? 'active' : ''}}"><a href="{{ url('/courses') }}">Vakken</a></li>
								@endcan

								@can("viewAny", \App\Models\Action::class)
									<li class="{{ substr(Request::path(),0,7) == 'actions' ? 'active' : ''}}"><a href="{{ url('/actions') }}">Punten</a></li>
								@endcan

								<li class="{{ substr(Request::path(),0,13) == 'camp-year-map' ? 'active' : ''}}"><a href="{{ url('/camp-year-map') }}">Kampen op de kaart!</a></li>

								<li class="{{ substr(Request::path(),0,5) == 'roles' ? 'active' : ''}}"><a href="{{ url('/roles/explain') }}">Rollen en rechten</a></li>
							</ul>
						</li>
						@endrole
					</ul>

					<ul class="nav navbar-nav navbar-right">

						<li class="{{ substr(Request::path(),0,7) == 'profile' ? 'active' : ''}}"><a href="{{ url('/profile') }}">Mijn profiel</a></li>

						<li><a href="{{ url('/logout') }}">Uitloggen</a></li>

					</ul>
				</div>
			</div>
		</nav>
