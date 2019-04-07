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
						
						@if (Auth::user()->is_admin)
						<li class="{{ substr(Request::path(),0,12) == 'participants' ? 'active' : ''}}"><a href="{{ url('/participants') }}">Deelnemers</a></li>
						<li class="{{ substr(Request::path(),0,7) == 'members' ? 'active' : ''}}"><a href="{{ url('/members') }}">Leden</a></li>
						<li class="{{ substr(Request::path(),0,6) == 'events' ? 'active' : ''}}"><a href="{{ url('/events') }}">Evenementen</a></li>
						<li class="{{ substr(Request::path(),0,9) == 'locations' ? 'active' : ''}}"><a href="{{ url('/locations') }}">Locaties</a></li>
						<li class="{{ substr(Request::path(),0,7) == 'courses' ? 'active' : ''}}"><a href="{{ url('/courses') }}">Vakken</a></li>
						<li class="{{ substr(Request::path(),0,7) == 'actions' ? 'active' : ''}}"><a href="{{ url('/actions') }}">Punten</a></li>
						<li class="{{ substr(Request::path(),0,5) == 'lists' ? 'active' : ''}}"><a href="{{ url('/lists') }}">Lijsten</a></li>
						<li class="{{ substr(Request::path(),0,6) == 'graphs' ? 'active' : ''}}"><a href="{{ url('/graphs') }}">Grafieken</a></li>
						<li class="{{ substr(Request::path(),0,5) == 'users' ? 'active' : ''}}"><a href="{{ url('/users') }}">Gebruikers</a></li>
						@endif

						@if (!Auth::user()->is_admin && Auth::user()->profile_type == "App\Member")
							<li class="{{ substr(Request::path(),0,7) == 'members' ? 'active' : ''}}"><a href="{{ url('/members') }}">Leden</a></li>
						@endif

					</ul>
					
					<ul class="nav navbar-nav navbar-right">
					
						<li class="{{ substr(Request::path(),0,7) == 'profile' ? 'active' : ''}}"><a href="{{ url('/profile') }}">Mijn profiel</a></li>
						
						<li><a href="{{ url('/auth/logout') }}">Uitloggen</a></li>
						
					</ul>
				</div>
			</div>
		</nav>