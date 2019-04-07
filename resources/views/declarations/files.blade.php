@extends('master')

@section('title')
	Mijn bestanden
@endsection

@section('content')
<!-- Dit is de beheerpagina voor bestanden voor je declaraties -->

<h1>Mijn bestanden</h1>

<hr/>

<!-- Bestandentabel -->
<table class="table table-hover" id="filesTable" data-page-length="25">
	<thead>
		<tr>
			<th>Naam</th>
			<th># declaraties</th>
			<th>Laatst gewijzigd</th>
			<th></th>
		</tr>
	</thead>
	
	<tbody>
		@foreach ($items as $item)
			<tr>
				<td>
					<a href="{{ asset('uploads/declarations/' . $member->id . '/' . $item['filename']) }}" target="_blank">{{$item['filename']}}</a>
				</td>
				<td>{{$item['num_declarations']}}</td>
				<td>{{$item['date_modified']}}</td>
				<td><a href="{{ url('/declarations/files', [$item['filename'], 'delete']) }}"><span class="glyphicon glyphicon-remove" data-toggle="tooltip" title="Verwijderen"></span></a></td>
			</tr>
		@endforeach
	</tbody>
</table>

@endsection

@section('footer')
<script type="text/javascript">
$( document ).ready(function() {
    $('#filesTable').DataTable({
		responsive: true,
		order: [[ 0, "asc" ]],
		columns: [
			null,
			null,
			null,
			{'orderable':false}
		]
	});
});
</script>
@endsection