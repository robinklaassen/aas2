<p>
	Beste {{ $user->profile->volnaam }},
</p>

<p>
	Zojuist is via het <a href="https://aas2.anderwijs.nl" target="_blank">administratiesysteem</a> van Anderwijs het wachtwoord van je account gereset. Je kunt nu inloggen met de onderstaande gegevens. Het is handig om direct in te loggen en een nieuw, persoonlijk wachtwoord te kiezen.
	<br/><br/>
	Gebruikersnaam: {{$user->username}}<br/>
	Wachtwoord: {{$password}}
</p>

<p>
	Met vriendelijke groet,<br/>
	Anderwijs
</p>