@extends('master')

@section('title')
Rollen en Rechten
@endsection

@section('content')
<div class="c-roles-explanation">
    <table class="c-roles-explanation__table">
        <thead>
            <tr>
                <th>
                    <!-- Capability description -->
                </th>
                @foreach($roles as $role)
                    <th>{{$role->title}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($capabilities as $capability)
            <tr>
                <th>{{ $capability->description }}</th>
                @foreach($roles as $role)
                    <td class="c-roles-explanation__table__cell @if($capabilitiesPerRole[$role->id]->contains($capability->id)) --active @endif"></td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

