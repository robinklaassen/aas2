@extends('master')

@section('title')
Privacy statement
@endsection

@php
$qs = 'origin=' . urlencode($origin);
@endphp
@section('content')
<div class="panel panel-default">
    <div class="panel-heading" style="height:62px">
        {{ Form::model($user, ['METHOD' => 'POST', 'url' => 'privacy?' . $qs,]) }}
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