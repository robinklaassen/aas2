@extends('master')

@section('title')
	Deelnemers anonimiseren
@endsection

@section('content')
    Weet je zeker dat je {{ count($participants) }} deelnemers wil verwijderen?

    <ul>
        @foreach ($participants as $participant)
            <li>{{$participant->volnaam}}</li>
        @endforeach
    </ul>
@endsection
