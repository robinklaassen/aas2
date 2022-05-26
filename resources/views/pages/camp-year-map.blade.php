@extends('master')

@section('title')
Kampen door de jaren heen
@endsection

@section('content')

<h1>Kampen door de jaren heen</h1>

<hr />

<camp-year-map :camp-data='@json($camps)'></camp-year-map>

@endsection
