@extends('master')

@section('title')
Leden zoeken op vaardigheden & interesses
@endsection

@section('content')

<h1>Leden zoeken op vaardigheden & interesses</h1>

<hr/>

@include('errors.list')

<form class="form-inline" method="get" action="#">

	<div class="form-group col-md-6">
		<label for="skills">Vaardigheden: </label>
		<select class="form-control" style="width: 100%;" name="skills[]" id="skills" multiple>
			@foreach ($all_skills as $id => $skill)
			<option value="{{$id}}" @if (in_array($id, $skills)) selected @endif>{{$skill}}</option>
			@endforeach
		</select>
    </div>
    
    <div class="form-group col-md-4">
        <label class="radio-inline">
            <input type="radio" name="require_how" value="any" @if ($require_how == 'any') checked @endif>Eén van deze
        </label>
        <label class="radio-inline">
            <input type="radio" name="require_how" value="all" @if ($require_how == 'all') checked @endif>Alle
        </label>
    </div>

    <div class="col-md-2">
	    <button type="submit" class="btn btn-primary">Zoeken</button>
    </div>
</form>

@unless ($members->isEmpty())

<hr/>

<table class="table table-hover">
    <thead>
        <th>Naam</th>
        <th>Soort lid</th>
        <th>Vaardigheden & interesses</th>
    </thead>
    <tbody>
        @forelse ($members as $m)
        <tr>
            <td><a href="{{ url('/members', $m->id) }}">{{$m->volnaam}}</a></td>
            <td>{{$m->soort}}</td>
            <td>{{ $m->skills()->pluck('tag')->implode(', ') }}</td>
        </tr>
        @empty
            Geen leden gevonden
        @endforelse
    </tbody>
</table>

@endunless

@endsection

@section('footer')
<script type="text/javascript">
	$(document).ready(function() {
	$("#skills").select2();
});
</script>
@endsection