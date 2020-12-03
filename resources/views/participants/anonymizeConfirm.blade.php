@extends('master')

@section('title')
	Deelnemers anonimiseren
@endsection

@section('content')
    Weet je zeker dat je {{ count($participants) }} deelnemers wil anonimiseren?

    <form method="post">
        <ul>
            @foreach ($participants as $participant)
                <input type="hidden" name="participants[]" value="{{$participant->id}}" />
                <li>{{$participant->volnaam}}</li>
            @endforeach
        </ul>


        <input type="submit" value="Ja, anonimiseren!" class="btn btn-danger" />
    </form>
@endsection
