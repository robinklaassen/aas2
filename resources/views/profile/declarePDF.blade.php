<h1>Declaratie van {{$member->voornaam}} {{$member->tussenvoegsel}} {{$member->achternaam}}</h1>

<h3>d.d. {{date('Y-m-d')}}</h3>

<table border="0" cellpadding="5" cellspacing="0" style="width:100%;">
	<thead>
		<tr>
			<th>Bestand</th>
			<th>Datum</th>
			<th>Omschrijving</th>
			<th>Bedrag</th>
			<th>Gift</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($inputData as $data)
		<tr>
			<td align="center">{{$data['fileNumber']}}</td>
			<td align="center" style="white-space:nowrap;">{{$data['date']}}</td>
			<td align="center">{{$data['description']}}</td>
			<td align="center">{{$data['amount']}}</td>
			<td align="center">{{ ($data['gift'] == 1) ? 'Ja' : 'Nee' }}</td>
		</tr>
		@endforeach
	</tbody>
</table>



<p>
	<br/><br/>
	Totaalbedrag: <b>&euro; {{$totalAmount}}</b>
</p>

<p style="font-size:70%;">
	Deze declaratie is automatisch gegenereerd door AAS 2.0.
</p>