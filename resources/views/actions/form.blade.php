<p class="well">
	<b>Let op!</b> Punten voor een <b>kamp</b> (zowel volle als wisselleiding) en een <b>training</b> worden al automatisch toegekend door AAS 2.0.
</p>

<div class="row">
	<div class="col-sm-2 form-group">
		{!! Form::label('date', 'Datum:') !!}
		@if (isset($action))
			{!! Form::input('date', 'date', $action->date->format('Y-m-d'), ['class' => 'form-control', 'placeholder' => 'Format: jjjj-mm-dd']) !!}
		@else
			{!! Form::input('date', 'date', null, ['class' => 'form-control', 'placeholder' => 'Format: jjjj-mm-dd']) !!}
		@endif
	</div>
	
	<div class="col-sm-3 form-group">
		{!! Form::label('member_id', 'Lid:') !!}
		{!! Form::select('member_id', $members, null, ['class' => 'form-control']) !!}
	</div>
	
	<div class="col-sm-5 form-group">
		{!! Form::label('description', 'Omschrijving:') !!}
		{!! Form::text('description', null, ['class' => 'form-control']) !!}
	</div>
	
	<div class="col-sm-2 form-group">
		{!! Form::label('points', 'Punten:') !!}
		{!! Form::input('number', 'points', null, ['class' => 'form-control', 'min' => 0]) !!}
	</div>
</div>

<div class="row">
	<div class="col-sm-5 form-group">
		{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
	</div>
</div>