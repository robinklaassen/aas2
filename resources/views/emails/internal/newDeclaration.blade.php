<p>
    Geachte penningmeester,
</p>

<p>
    <b>{{$member->volnaam}}</b> heeft zojuist de volgende <b>declaratie</b> gedaan via AAS 2.0:
</p>

<table border="0" cellpadding="5" cellspacing="0" width="800">
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
    Indien akkoord mag het totaalbedrag van <b>&euro; {{$totalAmount}}</b> worden overgemaakt op rekening <b>{{$member->iban}}</b>.
</p>

<p>
    Met vriendelijke groet,<br />
    AAS 2.0
</p>