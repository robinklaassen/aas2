BEGIN:VCALENDAR
METHOD:PUBLISH
VERSION:2.0
PRODID:-//Vereniging Anderwijs//Evenementenkalender//NL
@foreach ($events as $event)
BEGIN:VEVENT
SUMMARY:{{$event->naam}}
UID:e{{$event->id}}
STATUS:CONFIRMED
DTSTART;TZID=Europe/Amsterdam:{{$event->datum_start->format("Ymd")}}T{{str_replace(":","",$event->tijd_start)}}
DTEND;TZID=Europe/Amsterdam:{{$event->datum_eind->format("Ymd")}}T{{str_replace(":","",$event->tijd_eind)}}
LOCATION:{{$event->location->adres}}, {{$event->location->postcode}} {{$event->location->plaats}}
END:VEVENT
@endforeach
@foreach ($members as $member)
BEGIN:VEVENT
SUMMARY:Verjaardag {{$member->volnaam}}
UID:m{{$member->id}}
STATUS:CONFIRMED
DTSTART;VALUE=DATE:{{$member->geboortedatum->format("Ymd")}}
RRULE:FREQ=YEARLY
END:VEVENT
@endforeach
END:VCALENDAR