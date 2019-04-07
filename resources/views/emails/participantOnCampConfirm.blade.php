<p>
	Geachte ouder/verzorger,
</p>

<p>
	Zojuist heeft u uw kind via zijn/haar profiel aangemeld voor het volgende Anderwijskamp:
</p>

<p>
	KAMP<br/>
	Naam kamp: {{ $camp->naam }}<br/>
	Locatie: {{ $camp->location->plaats }}<br/>
	Startdatum: {{ $camp->datum_start->format('d-m-Y') }}<br/>
	Einddatum: {{ $camp->datum_eind->format('d-m-Y') }}
</p>

<p>
	VAKKEN<br/>
	@foreach ($givenCourses as $i => $course)
		<br/>
		Vak {{$i + 1}}: {{ $course['naam'] }}<br/>
		Info: {{ $course['info'] }}
	@endforeach
</p>

<p>
	--------------------
</p>

<p>
	U bevindt zich nu in de eerste stap van het plaatsingsproces van uw kind voor een Anderwijskamp. Voor meer informatie over het proces, dat bestaat uit vier stappen, klikt u <a href="http://www.anderwijs.nl/inschrijven/stappenplan">hier</a>.
</p>

@if ($camp->prijs == 0)
	<p>
		Uw kind staat op dit moment voorlopig ingeschreven voor het kamp. Om de inschrijving definitief te maken, dient u het kampgeld over te maken op onze rekening. <strong>Voor dit kamp is het kampgeld echter nog niet definitief vastgesteld.</strong> Zodra dat is gebeurd, ontvangt u daarover per e-mail bericht.
	</p>
@else
	@if ($iDeal == 0)
		<p>
			Uw kind staat op dit moment voorlopig ingeschreven voor het kamp. Om de inschrijving definitief te maken, dient u het kampgeld zoals onderstaand over te maken op onze rekening. Plaatsing van deelnemers op een kamp gebeurt op volgorde van betaling, dus wacht hier niet te lang mee. Uiterlijk twee weken nadat u het kampgeld heeft overgemaakt, ontvangt u per e-mail een bevestiging van de inschrijving.
		</p>
	@else
		<p>
			U heeft aangegeven het kampgeld direct via iDeal te betalen. Wanneer dit succesvol ontvangen is, ontvangt u een aparte bevestiging daarvan. Is er onverhoopt toch iets misgegaan, dan dient u het kampgeld zoals onderstaand over te maken op onze rekening. Plaatsing van deelnemers op een kamp gebeurt op volgorde van betaling, dus wacht hier niet te lang mee. Uiterlijk twee weken nadat u het kampgeld heeft overgemaakt, ontvangt u per e-mail een bevestiging van de inschrijving.
		</p>
	@endif
	
	<p>
		BETALINGSINFORMATIE<br/>
		Te betalen bedrag: € {{ $toPay }}<br/>
		Rekeningnummer: NL68 TRIO 0198 4197 83 t.n.v. Vereniging Anderwijs te Utrecht<br/>
		Onder vermelding van: naam deelnemer + deze kampcode: {{ $camp->code }}
	</p>
@endif

@if ($type == 'new' && $participant->inkomen)
	<p>
		U heeft een korting op de kampprijs aangevraagd. Daarvoor dient u ons een bewijs van inkomen te sturen. Een kopie van bijvoorbeeld uw loonstrook en die van uw eventuele partner is voldoende. Vermeld daarbij de samenstelling van uw gezin en eventuele andere zaken die van belang kunnen zijn voor de toekenning van de korting. U kunt dit sturen naar onderstaand adres. Let op: het draait om het <strong>bruto gezinsinkomen</strong> en niet om het netto gezinsinkomen! Na beoordeling van de kortingsaanvraag nemen wij zonodig contact met u op.
	</p>
	<p>
		Vereniging Anderwijs<br/>
		T.a.v. de penningmeester<br/>
		Postbus 13228<br/>
		3507 LE Utrecht
	</p>
@endif

<p>
	Verder delen we u mee dat na de dagtekening van de plaatsing van uw kind (stap 3 in het proces), uw inschrijving onherroepelijk is. Dit wil zeggen dat wanneer u zich na het ontvangen van de plaatsingsmail afmeldt, u het hele kampbedrag dient te betalen. Wanneer u zich eerder afmeldt, dient u slechts de administratiekosten van 50 euro te betalen.
</p>

<p>
	Als u nog vragen heeft, dan kunt u altijd contact met ons opnemen door te mailen naar <a href="mailto:kantoor@anderwijs.nl">kantoor@anderwijs.nl</a>. Als u liever telefonisch contact wilt, mail dan uw naam en telefoonnummer naar hetzelfde e-mailadres en we bellen u vervolgens zo snel mogelijk.
</p>

<p>
	Met vriendelijke groet,<br/>
	Anderwijs
</p>