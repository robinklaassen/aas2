<h1>Declaratie van {{$member->volnaam}}</h1>

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
		@foreach ($declarations as $d)
		<tr>
			<td align="center">{{ ($d->filename == null) ? '-' : $d->filename }}</td>
			<td align="center" style="white-space:nowrap;">{{ $d->date->format('d-m-Y') }}</td>
			<td align="center">{{ $d->description }}</td>
			<td align="center">@money($d->amount)</td>
			<td align="center">{{ ($d->gift == 1) ? 'Ja' : 'Nee' }}</td>
		</tr>
		@endforeach
	</tbody>
</table>



<p>
	<br/><br/>
	Totaalbedrag (ex. giften): <b>@money($total)</b>
</p>

<p style="font-size:70%;">
	Deze declaratie is automatisch gegenereerd door AAS 2.0.
</p>