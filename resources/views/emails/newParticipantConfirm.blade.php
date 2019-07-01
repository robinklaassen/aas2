<p>
	Geachte ouder/verzorger,
</p>

<p>
	Zojuist heeft u uw kind opgegeven voor een Anderwijskamp, met de volgende gegevens:
</p>

<p>
	PERSOONS- EN CONTACTGEGEVENS DEELNEMER<br/>
	Naam: {{ $participant->voornaam }} {{ $participant->tussenvoegsel }} {{ $participant->achternaam }}<br/>
	Geboortedatum: {{ $participant->geboortedatum->format('d-m-Y') }}<br/>
	Geslacht: {{ ($participant->geslacht == 'M') ? 'man' : 'vrouw' }}<br/>
	Telefoonnummer: {{ $participant->telefoon_deelnemer }}<br/>
	Emailadres: {{ $participant->email_deelnemer }}
</p>

<p>
	CONTACTGEGEVENS OUDER<br/>
	Adres: {{ $participant->adres }}<br/>
	Postcode: {{ $participant->postcode }}<br/>
	Woonplaats: {{ $participant->plaats }}<br/>
	Telefoonnummer vast: {{ $participant->telefoon_ouder_vast }}<br/>
	Telefoonnummer mobiel: {{ $participant->telefoon_ouder_mobiel }}<br/>
	Emailadres: {{ $participant->email_ouder }}
</p>

<p>
	KAMP<br/>
	Naam kamp: {{ $camp->naam }}<br/>
	Locatie: {{ $camp->location->plaats }}<br/>
	Startdatum: {{ $camp->datum_start->format('d-m-Y') }}<br/>
	Einddatum: {{ $camp->datum_eind->format('d-m-Y') }}
</p>

<p>
	KORTINGSREGELING<br/>
	Bruto maandinkomen: {{ $incomeTable[$participant->inkomen] }}
</p>

<p>
	BIJSPIJKERINFORMATIE<br/>
	Naam school: {{ $participant->school }}<br/>
	Niveau: {{ $participant->niveau }}<br/>
	Klas: {{ $participant->klas }}
	@foreach ($givenCourses as $i => $course)
		<br/>
		Vak {{$i + 1}}: {{ $course['naam'] }}<br/>
		Info: {{ $course['info'] }}
	@endforeach
</p>

<p>
	OVERIGE INFORMATIE<br/>
	Hoe bij Anderwijs terechtgekomen: {{ $participant->hoebij }}<br/>
	Opmerkingen: {{ $participant->opmerkingen }}
</p>

<p>
	--------------------
</p>

<p>
	Er is automatisch een account aangemaakt. Hiermee kunt u inloggen op ons <a href="https://aas2.anderwijs.nl">administratiesysteem</a> om de gegevens van uw kind te beheren en in de toekomst makkelijk vaker uw kind op kamp te sturen. Ook kunt u de opgegeven vakken voor een kamp en de toelichting daarop wijzigen - Anderwijs wordt hiervan automatisch op de hoogte gesteld. U wordt geadviseerd om direct in te loggen en een nieuw, persoonlijk wachtwoord te kiezen.
	<br/><br/>
	Gebruikersnaam: {{$username}}<br/>
	Wachtwoord: {{$password}}
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
	@if ($ideal == 0)
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

@if ($participant->inkomen)
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