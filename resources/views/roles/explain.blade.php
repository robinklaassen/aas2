@extends('master')

@section('title')
Rollen en rechten
@endsection

@section('content')
<roles-table
    :roles='@json($roles)'
    :capabilities='@json($capabilities)'
    :capabilities-per-role='@json($capabilitiesPerRole)'
></roles-table>
@endsection
