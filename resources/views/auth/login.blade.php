@extends('master')

@section('title')
	Inloggen
@endsection

@section('content')
	<div class="row" style="margin-top:50px;">
		<div class="col-md-8 col-md-offset-2">
			<h1>Anderwijs Administratie Systeem (AAS) 2.0</h1>
			<p>Hier per toeval terechtgekomen en geen flauw idee waar je bent? <a href="http://www.anderwijs.nl">Ga naar onze website.</a></p>
			<hr/>
			<div class="panel panel-default">
				<div class="panel-heading">Inloggen</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger alert-important">
							<strong>Oeps!</strong> Er waren wat problemen met je invoer.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label for="username" class="col-md-4 control-label">Gebruikersnaam</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="username" id ="username" value="{{ old('username') }}">
							</div>
							
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<p class="help-block">Je gebruikersnaam is de eerste letter van je voornaam, plus je hele achternaam zonder tussenvoegsels. Voorbeeld: Ans van Berlo wordt 'aberlo'.</p>
							</div>
						</div>

						<div class="form-group">
							<label for="password" class="col-md-4 control-label">Wachtwoord</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password" id="password">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<div class="checkbox" style="float:left;">
									<label>
										<input type="checkbox" name="remember"> Onthouden
									</label>
								</div>
								<div style="float:left; margin:12px 0 0 10px;">
									<span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" title="Niet aanvinken wanneer je je op een openbare pc bevindt!"></span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">Inloggen</button>
								<a class="btn btn-link" href="{{ url('/forgot-password') }}">Wachtwoord
								vergeten?</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
