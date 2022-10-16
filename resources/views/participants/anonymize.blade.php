@extends('master')

@section('title')
	Deelnemers anonimiseren
@endsection


@section('content')
    <form action="{{ action('ParticipantsController@anonymizeConfirm') }}">
        @csrf
        <table class="table table-hover">
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
                <td><input type="checkbox" name="participant[]" value="{{ $participant->id }}"  checked /></td>
                <td>{{$participant->volnaam}}</td>
                <td>{{$participant->created_at}}</td>
                <td>{{$participant->lastPlacedCamp !== null ? $participant->lastPlacedCamp->code : ""}}</td>
            </tr>
        @endforeach
        </table>
        <input type="submit" value="Anonimiseren" class="btn btn-primary" />
    </form>
@endsection
