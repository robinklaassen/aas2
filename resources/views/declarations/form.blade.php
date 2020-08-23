
<div class="form-group">
    {!! Form::label('date', 'Datum:') !!}
        {!! Form::input('date', 'date', date('Y-m-d'), ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    <label for="amount">Bedrag:</label>
    <div class="input-group">
        <span class="input-group-addon">&euro;</span>
        {!! Form::input('number', 'amount', null, ['class' => 'form-control', 'placeholder' => '0.00', 'step' => 'any' ]) !!}
    </div>
</div>
    
<div class="form-group">
    {!! Form::label('gift', 'Gift:') !!}<br/>
    {!! Form::hidden('gift', 0) !!}
    {!! Form::checkbox('gift', 1, null, ['style' => 'margin-top: 14px;']) !!}
</div>

<div class="form-group">
    {!! Form::label('description', 'Omschrijving:') !!}
    {!! Form::text('description', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group declaration-form-image">
    <label>
        Bestand<br />
        @if (isset($declaration))
            <div class="declaration-form-image-image">
                <img class="img-thumbnail" src="/declarations/{{$declaration->id}}/file" />
                <span>{{  $declaration->original_filename }}</span>
            </div>
        @endif
        {!! Form::file('image') !!}
    </label>
</div>

<div class="form-group">
    {!! Form::submit('Opslaan', ['class' => 'btn btn-primary form-control']) !!}
</div>
