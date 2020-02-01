@extends('master')

@section('title')
Pagina in onderhoud
@endsection

@section('content')

<div class="jumbotron">
    <h1>
        Deze pagina is in onderhoud.
    </h1>

    <p>
        Deze pagina is tijdelijk niet beschikbaar.<br />
    </p>
    <p><small>
        We zijn constant bezig om AAS te verbeteren en we doen ons best om dit zo snel mogelijk te doen. <br />
        Mocht je hem nodig hebben, laat het <a href="mailto:aasbazen@anderwijs.nl">ons</a> weten.
    </small></p>

    <p><a class="btn btn-primary btn-lg" href="{{ url()->previous() }}" role="button">Terug</a></p>
</div>

@endsection