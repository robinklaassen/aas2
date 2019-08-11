@extends('master')

@section('title')
Privacy statement
@endsection

@php
$qs = isset($origin) ? 'origin=' . urlencode($origin) : '';
$privacy_md = file_get_contents(resource_path("\\markdown\\privacy-statement.md"));
@endphp
@section('content')
<div class="panel panel-default">
    @if(isset($showForm) && $showForm)
    <div class="panel-heading" style="height:62px">
        {{ Form::model($user, ['METHOD' => 'POST', 'url' => 'accept-privacy?' . $qs,]) }}
        <label>
            {{ Form::checkbox('privacyAccepted', 1) }}
            Ik geef Anderwijs toestemming om mijn gegevens te verwerken zoals beschreven in het onderstaande privacystatement
        </label>
        {{ Form::submit('Opslaan', [ "class" => "btn btn-primary pull-right" ]) }}
        {{ Form::close() }}
    </div>
    @endif
    <div class="panel-body">
        @markdown($privacy_md)
    </div>
</div>
@endsection