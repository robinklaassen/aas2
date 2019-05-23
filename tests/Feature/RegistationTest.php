<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistationTest extends TestCase
{

    private $data = [
        "voornaam" => "Keesje",
        "tussenvoegsel" => "",
        "achternaam" => "Test",
        "geboortedatum" => "2001-01-01",
        "geslacht" => "M",
        "telefoon_deelnemer" => "0612345678",
        "email_deelnemer" => "keesje@test.com",
        "adres" => "TestLaan",
        "postcode" => "1111AA",
        "plaats" => "Zwapperdam",
        "telefoon_ouder_vast" => "0361234567",
        "telefoon_ouder_mobiel" => "0612345678",
        "email_ouder" => "keesje@test.com",
        "mag_gemaild" => "0",
        "selected_camp" => "5",
        "inkomen" => "0",
        "school" => "HierLeerJeNietsSchool",
        "niveau" => "VMBO",
        "klas" => "2",
        "vak0" => "3",
        "vakinfo0" => "zoiets",
        "vak1" => "3",
        "vakinfo1" => "Daar snap ik toch geen kut van joh, iets met E=MC2",
        "vak2" => "0",
        "vakinfo2" => "",
        "vak3" => "0",
        "vakinfo3" => "",
        "vak4" => "0",
        "vakinfo4" => "",
        "vak5" => "0",
        "vakinfo5" => "",
        "iDeal" => "1",
        "hoebij[]" => "Nieuwsbrief school",
        "hoebij_anders" => "",
        "opmerkingen" => "",
        "voorwaarden" => "1",
        "privacy" => "1"
    ];

    public function testParticipantRegistrationWithIDeal()
    {
        $response = $this->post('/register-participant', $this->data);

        $response->assertStatus(200);
    }
}
