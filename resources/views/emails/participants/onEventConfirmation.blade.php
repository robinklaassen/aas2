<p>
	Geachte ouder/verzorger,
</p>

<p>
	Zojuist heeft u uw kind via zijn/haar profiel aangemeld voor het volgende Anderwijskamp:
</p>

<p>
	KAMP<br/>
	Naam kamp: {{ $event->naam }}<br/>
	Locatie: {{ $event->location->plaats }}<br/>
	Startdatum: {{ $event->datum_start->format('d-m-Y') }}<br/>
	Einddatum: {{ $event->datum_eind->format('d-m-Y') }}
</p>

<p>
	Onderaan dit bericht kunt u zien voor welke vakken u uw kind heeft opgegeven.
</p>

@if ($toPay == 0)
	<p>
		Uw kind staat op dit moment voorlopig ingeschreven voor het kamp. Om de inschrijving definitief te maken, dient u het kampgeld over te maken op onze rekening. <strong>Voor dit kamp is het kampgeld echter nog niet definitief vastgesteld.</strong> Zodra het kampgeld bekend is, ontvangt u daarover per e-mail bericht.
	</p>
@else
	@if ($iDeal == 0)
		<p>
			Uw kind staat op dit moment voorlopig ingeschreven voor het kamp. Om de inschrijving definitief te maken, dient u het kampgeld zoals hieronder vermeld over te maken op onze rekening. Beschikbare plaatsen op een kamp worden vergeven op volgorde van betaling, dus wacht hier niet te lang mee. Uiterlijk twee weken nadat u het kampgeld heeft overgemaakt, ontvangt u per e-mail een bevestiging van de inschrijving.
		</p>
	@else
		<p>
			U heeft aangegeven het kampgeld direct via iDeal te betalen. Wanneer dit succesvol ontvangen is, ontvangt u een aparte bevestiging daarvan. Is er onverhoopt toch iets misgegaan, dan dient u het kampgeld zoals hieronder vermeld over te maken op onze rekening. Beschikbare plaatsen op een kamp worden vegeven op volgorde van betaling, dus wacht hier niet te lang mee. Uiterlijk twee weken nadat u het kampgeld heeft overgemaakt, ontvangt u per e-mail een bevestiging van de inschrijving.
		</p>
	@endif
	
	<p>
		BETALINGSINFORMATIE<br/>
		Te betalen bedrag: € {{ $toPay }}<br/>
		Rekeningnummer: NL68 TRIO 0198 4197 83 t.n.v. Vereniging Anderwijs te Utrecht<br/>
		Onder vermelding van: naam deelnemer + deze kampcode: {{ $event->code }}
	</p>
@endif

@if ($type == 'new' && $participant->inkomen)
	<p>
		U heeft een korting op de kampprijs aangevraagd. Daarvoor hebben wij een bewijs van uw inkomen nodig. Een kopie van bijvoorbeeld uw loonstrook en die van uw eventuele partner is voldoende. Stuur deze naar ons op en vermeld daarbij de samenstelling van uw gezin en eventuele andere zaken die van belang kunnen zijn voor de toekenning van de korting. U kunt dit per mail sturen naar <a href="mailto:penningmeester@anderwijs.nl">penningmeester@anderwijs.nl</a> of per post naar onderstaand adres. Let op: het gaat hier om het <strong>bruto gezinsinkomen</strong> en niet om het netto gezinsinkomen! 
	</p>
	<p>
		Vereniging Anderwijs<br/>
		T.a.v. de penningmeester<br/>
		Postbus 13228<br/>
		3507 LE Utrecht
	</p>
@endif

<p>
	Verder willen we u laten weten dat uw inschrijving onherroepelijk is, zodra wij uw kind geplaatst hebben voor het kamp <a href="https://www.anderwijs.nl/inschrijven/inschrijven-scholieren/">(stap 3 in het proces)</a>. Dit wil zeggen dat u het hele kampbedrag dient te betalen, als u zich na het ontvangen van de plaatsingsmail afmeldt. Wanneer u zich eerder afmeldt, dient u slechts de administratiekosten van 50 euro te betalen.
</p>

<p>
	Als u nog vragen heeft, dan kunt u altijd contact met ons opnemen door te mailen naar <a href="mailto:kantoor@anderwijs.nl">kantoor@anderwijs.nl</a>. Als u liever telefonisch contact wilt, mail dan uw naam en telefoonnummer naar hetzelfde e-mailadres en we bellen u vervolgens zo snel mogelijk.
</p>

<p>
	Met vriendelijke groet,<br/>
	Anderwijs
</p>

<p>
	-------------
</p>

<p>
	INFORMATIE OPGEGEVEN VAKKEN<br/>
	@foreach ($givenCourses as $i => $course)
		<br/>
		Vak {{$i + 1}}: {{ $course['naam'] }}<br/>
		Info: {{ $course['info'] }}
	@endforeach
</p>
