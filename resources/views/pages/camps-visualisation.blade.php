@extends('master')

@section('title')
Kampvisualisatie
@endsection

@section('content')

<h1>Kampvisualisatie</h1>

<hr />

<camp-year-map :camp-data='@json($camps)'></camp-year-map>

@endsection
