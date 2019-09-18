<div class="form-group">
    {!! Form::label('text', 'Informatie:') !!}
    {!! Form::textarea('text', null, ['class' => 'form-control']) !!}
</div>

@if(\Auth::user()->is_admin == 2)
<div class="checkbox">
    <label title="Alleen zichtbaar voor admin-plus (bestuur + kamp commissie)">
        {!! Form::hidden('is_secret', 0) !!}
        {!! Form::checkbox('is_secret', 1, null) !!}
        Geheim <i class="glyphicon glyphicon-question-sign"></i>
    </label>
</div>
@endif

<div class="form-group">
    {!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
</div>