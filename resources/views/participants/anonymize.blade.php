@extends('master')

@section('title')
	Deelnemers anonimiseren
@endsection


@section('content')
    <form action="{{ action('ParticipantsController@anonymizeStore') }}" method="post">
        <input type="submit" value="Anonimiseren" />
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Naam</th>
                    <th>Lid sinds</th>
                    <th>Laatste kamp</th>

                </tr>
            </thead>
        @foreach ($participants as $participant)

            <tr>
                <td><input name="participant[]" value="{{ $participant->id }}"  /></td>
                <td>{{$participant->volnaam}}</td>
                <td>{{$participant->created_at}}</td>
                <td>{{$participant->lastPlacedCamp->code ($participant->lastPlacedCamp->datum_start)}}</td>
            </tr>
        @endforeach
        </table>
    </form>
@endsection
