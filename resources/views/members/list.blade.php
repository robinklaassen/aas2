@extends('master')

@section('title')
	Ledenlijst
@endsection

@section('content')

<h1>Ledenlijst</h1>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Voornaam</th>
            <th></th>
            <th>Achternaam</th>
            <th>Adres</th>
            <th>Postcode</th>
            <th>Woonplaats</th>
            <th>Telefoon</th>
            <th>Soort lid</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($members as $m)
            <tr>
                <td>{{ $m->voornaam }}</td>
                <td>{{ $m->tussenvoegsel }}</td>
                <td>{{ $m->achternaam }}</td>
                <td>{{ $m->adres }}</td>
                <td>{{ $m->postcode }}</td>
                <td>{{ $m->plaats }}</td>
                <td>{{ $m->telefoon }}</td>
                <td>{{ $m->soort }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection