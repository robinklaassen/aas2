# AAS 2.0
[![Build Status](https://travis-ci.com/robinklaassen/aas2.svg?branch=master)](https://travis-ci.com/robinklaassen/aas2)

Custom administratiesysteem voor vereniging Anderwijs, www.anderwijs.nl.  
Gebouwd april-mei 2015 door Robin Klaassen. Gebaseerd op Laravel 5.

Hieronder globale informatie over het opzetten van een lokale ontwikkelomgeving. Voor een uitgebreider stappenplan in het Engels, zie de file `INSTALLATION.md`.

## Draai de code lokaal

- Installeer WAMP
- Installeer Composer; dit kan globaal of lokaal met een `composer.phar` bestand in deze repo (zie https://getcomposer.org/download/)
- Clone dit project
- `$ composer install` haalt de dependencies binnen van de source code en maakt een lokale `.env` file aan
- Maak in WAMP een Virtual Host aan die verwijst naar de `public` map van dit project
- Open het aangemaakte adres in je favoriete browser

_Protip: [Laravel IDE helper generator](https://github.com/barryvdh/laravel-ide-helper) zou vermoedelijk je IDE significant kunnen verbeteren, o.a. code completion, linking van variablen in verschillende soorten bestanden._

## Database verbinden

- Maak een nieuwe gebruiker met eigen database aan in PhpMyAdmin
- Schrijf de inloggegevens in `.env` in de projectfolder
- Maak en vul de tabellen met: `$ php artisan migrate --seed`  

## Testdata updaten

`$ php artisan migrate:fresh --seed` verwijdert alle tabellen, maakt ze opnieuw aan en vult ze met dummy data
