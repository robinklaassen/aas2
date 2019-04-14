# AAS 2.0

Custom administratiesysteem voor vereniging Anderwijs, www.anderwijs.nl.  
Gebouwd april-mei 2015 door Robin Klaassen. Gebaseerd op Laravel 5.

## Draai de code lokaal

- Installeer WAMP
- Installeer Composer
- `$ composer create-project --prefer-dist laravel/laravel=5.1.* aas2`
- Clone dit project
- Kopieer de bestanden van dit project over de gegenereerde bestanden in de map `aas2` (die in de map van je WAMP-installatie staat)
- `$ composer update` haalt de dependencies binnen van de source code


_Protip: [Laravel IDE helper generator](https://github.com/barryvdh/laravel-ide-helper) zou vermoedelijk je IDE significant kunnen verbeteren, o.a. code completion, linking van variablen in verschillende soorten bestanden._

## Database verbinden
- Maak een nieuwe gebruiker met eigen database aan in PhpMyAdmin
- Schrijf de inloggegevens in `.env` in de projectfolder
- Vul de tabel met: `$ php artisan migrate --seed`  

## Testdata updaten

`$ php artisan migrate:refresh --seed`
