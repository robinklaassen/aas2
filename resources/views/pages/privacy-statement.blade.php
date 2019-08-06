@extends('master')

@section('title')
Privacy statement
@endsection

@section('content')

<div class="panel panel-default">
    <div class="panel-heading" style="height:62px">
        {{ Form::model(Auth::user(), ['METHOD' => 'POST', 'url' => 'privacy']) }}
        <label>

            {{ Form::checkbox('privacyAccepted', 1) }}
            Ik geef Anderwijs toestemming om mijn gegevens te verwerken zoals beschreven in het onderstaande privacystatement

        </label>
        {{ Form::submit('Opslaan', [ "class" => "btn btn-primary pull-right" ]) }}
        {{ Form::close() }}
    </div>
    <div class="panel-body">
        @markdown($privacy_md)
    </div>
</div>
@endsection