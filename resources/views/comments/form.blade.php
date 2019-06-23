
<div class="form-group">
    {!! Form::label('text', 'Opmerking:') !!}
    {!! Form::textarea('text', null, ['class' => 'form-control']) !!}
</div>

@if(\Auth::user()->is_admin)
<div class="checkbox">
    <label>
        {!! Form::hidden('is_secret', 0) !!}
        {!! Form::checkbox('is_secret', 1, null) !!}
        Verborgen
    </label>
</div>
@endif

<div class="form-group">
	{!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
</div>