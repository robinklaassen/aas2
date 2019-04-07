@extends('master')

@section('title')
	Wachtwoord resetten
@endsection

@section('content')
<div class="row" style="margin-top:50px;">
	<div class="col-md-8 col-md-offset-2">
		<p>Vul je gebruikersnaam en geboortedatum in en klik op 'reset wachtwoord'. Je krijgt dan een automatische email op het adres dat bij ons bekend is, met daarin een nieuw wachtwoord.</p>
		<p>Gaat er iets fout, neem dan contact op met de <a href="mailto:webmaster@anderwijs.nl">webmaster</a>.</p>
		<hr/>
		<div class="panel panel-default">
			<div class="panel-heading">Wachtwoord resetten</div>
			<div class="panel-body">
				@if (session('status'))
					<div class="alert alert-success">
						{{ session('status') }}
					</div>
				@endif

				@if (count($errors) > 0)
					<div class="alert alert-danger alert-important">
						<strong>Oeps</strong> Er waren wat problemen met je invoer.<br><br>
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif

				<form class="form-horizontal" role="form" method="POST" action="{{ url('/forgot-password') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">

					<div class="form-group">
						<label for="username" class="col-md-4 control-label">Gebruikersnaam</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="username" id="username" value="{{ old('username') }}">
						</div>
					</div>
					
					<div class="form-group">
						<label for="geboortedatum" class="col-md-4 control-label">Geboortedatum</label>
						<div class="col-md-6">
							<input type="date" class="form-control" name="geboortedatum" id="geboortedatum" value="{{ old('geboortedatum') }}">
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-6 col-md-offset-4">
							<button type="submit" class="btn btn-primary">
								Reset wachtwoord
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
