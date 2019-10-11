@if ($errors->any())
	<div class="alert alert-info alert-important">
		<p>Ho, er ging iets fout!</p>
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif