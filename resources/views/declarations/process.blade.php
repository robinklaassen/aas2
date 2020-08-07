@extends('master')

@section('title')
	Declaraties verwerken
@endsection

@section('content')
<!-- Dit is het formulier voor het verwerken van declaraties -->


<h1>Declaraties verwerken</h1>

<hr/>

@include ('errors.list')

{!! Form::open(['url' => 'declarations/process/', 'method' => 'POST']) !!}

    <p>Je staat op het punt om alle openstaande declaraties van <strong>{{$member->volnaam}}</strong> te verwerken.</p>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>
                    Datum
                </th>
                <th>
                    Bedrag
                </th>
                <th>
                    Omschrijving
                </th>
                <th>
                    Gift
                </th>
                <th>
                    Bestand
                </th>
            </tr>
        </thead>
        @foreach ($declarations as $declaration)
            {!! Form::hidden('selected[]', $declaration->id) !!}
            <tr>
                <td>{{ $declaration->date->format('Y-m-d') }}</td>
                <td>{{ formatPrice($declaration->amount) }}</td>
                <td>{{ $declaration->description }}</td>
                <td> 
                    @if($declaration->gift)
                        ja
                    @else
                        nee
                    @endif
                </td>
                <td>
                    @if ($declaration->filename)
                        <a href="{{ url('declarations/' . $declaration->id, 'file') }}" target="_blank">{{$declaration->original_filename}}</a>
                    @else
                        -
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
    
    <p>
        Totaal gedeclareerde bedrag zonder giften betreft {{ formatPrice($total) }}
    </p>
    <p>
        Bankrekening nummer <b>{{ $member->iban }}</b> ten name van <b>{{$member->volnaam}}</b>
    </p>

	<div class="row">
		<div class="col-sm-6 form-group">
			{!! Form::submit('Verwerken', ['class' => 'btn btn-primary form-control']) !!}
		</div>
	</div>
{!! Form::close() !!}

@endsection