@extends('master')

@section('title')
	Doorverwijzing
@endsection

@section('header')
<style>
body {
	font-size: 180%;
}

a.list-group-item h4.list-group-item-heading {
	font-size: 150% !important;
}
</style>
@endsection

@section('content')

<div class="row">
<div class="col-md-offset-2 col-md-8 text-center">
	
	<img src="https://www.anderwijs.nl/wp-content/uploads/2016/03/Test-6-2.png" alt="Logo Anderwijs" style="width:60%;margin-top:100px;">
	
	<h1 class="text-center" style="margin-top:50px; margin-bottom:20px; font-weight:400;">Leuk dat je mee wil op kamp!</h1>
	
	<h4 class="text-center">Kies een van de onderstaande opties om door te gaan.</h4>
	
	<hr>
	
	<div class="row" style="margin-top:30px;">
		<div class="col-md-6 list-group">
			<a href="https://aas2.anderwijs.nl/register-participant" class="list-group-item">
				<h4 class="list-group-item-heading text-right">
					Nieuw bij Anderwijs
				</h4>
				<p class="list-group-item-text text-right">
					Ik ben nog niet eerder met Anderwijs op kamp geweest, maar ik wil me graag opgeven! Ik geef mijn gegevens door en kan kiezen of ik nu of later voor het kamp betaal.
				</p>
			</a>
		</div>
		<div class="col-md-6 list-group">
			<a href="https://aas2.anderwijs.nl/auth/login" class="list-group-item">
				<h4 class="list-group-item-heading text-left">
					Eerder meegeweest
				</h4>
				<p class="list-group-item-text text-left">
					Ik ben al een keer eerder meegeweest op kamp, dus ik wil graag inloggen, mijn gegevens controleren en dan een nieuw kamp uitkiezen.
				</p>
			</a>
		</div>
	</div>
	
</div>
</div>
@endsection