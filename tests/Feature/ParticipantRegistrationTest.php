<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\Event;
use App\Participant;
use App\Helpers\Payment\PaymentInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Helpers\Payment\MolliePaymentProvider;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Mail;

class ParticipantRegistrationTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;

    private $data = [
        "voornaam" => "Keesje",
        "tussenvoegsel" => "",
        "achternaam" => "Test",
        "geboortedatum" => "2001-01-01",
        "geslacht" => "M",
        "telefoon_deelnemer" => "0612345678",
        "email_deelnemer" => "keesje@test.com",
        "adres" => "TestLaan",
        "postcode" => "1111 AA",
        "plaats" => "Zwapperdam",
        "telefoon_ouder_vast" => "0361234567",
        "telefoon_ouder_mobiel" => "0612345678",
        "email_ouder" => "kees@test.com",
        "mag_gemaild" => 0,
        "selected_camp" => 5,
        "inkomen" => 0,
        "school" => "HierLeerJeNietsSchool",
        "niveau" => "VMBO",
        "klas" => 2,
        "vak0" => 3,
        "vakinfo0" => "zoiets",
        "vak1" => 3,
        "vakinfo1" => "Daar snap ik toch geen kut van joh, iets met E=MC2",
        "vak2" => 0,
        "vakinfo2" => "",
        "vak3" => 0,
        "vakinfo3" => "",
        "vak4" => 0,
        "vakinfo4" => "",
        "vak5" => 0,
        "vakinfo5" => "",
        "iDeal" => 1,
        "hoebij" => ["Nieuwsbrief school"],
        "hoebij_anders" => "",
        "opmerkingen" => "Testerrrr",
        "voorwaarden" => 1,
        "privacy" => 1
    ];
    private $event;
    private $participantData;
    private $userData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->event = Event::findOrFail($this->data["selected_camp"]);
        $this->participantData = [
            "voornaam" => $this->data["voornaam"],
            "tussenvoegsel" => $this->data["tussenvoegsel"],
            "achternaam" => $this->data["achternaam"],
            "geboortedatum" => $this->data["geboortedatum"],
            "geslacht" => $this->data["geslacht"],
            "adres" => $this->data["adres"],
            "postcode" => $this->data["postcode"],
            "plaats" => $this->data["plaats"],
            "telefoon_deelnemer" => $this->data["telefoon_deelnemer"],
            "telefoon_ouder_vast" => $this->data["telefoon_ouder_vast"],
            "telefoon_ouder_mobiel" => $this->data["telefoon_ouder_mobiel"],
            "email_deelnemer" => $this->data["email_deelnemer"],
            "email_ouder" => $this->data["email_ouder"],
            "mag_gemaild" => $this->data["mag_gemaild"],
            "inkomen" => $this->data["inkomen"],
            "school" => $this->data["school"],
            "niveau" => $this->data["niveau"],
            "klas" => $this->data["klas"],
            "inkomensverklaring" => null,
            "opmerkingen" => $this->data["opmerkingen"],
        ];
        $username = strtolower(substr($this->data["voornaam"], 0, 1) . $this->data["achternaam"]);
        $this->userData = [
            "username" => $username,
            "is_admin" => 0
        ];
    }

    /**
     * Tests the participants registration with iDeal provided by mollie
     */
    public function testParticipantRegistrationWithIDeal()
    {
        Mail::fake();

        $this->instance(MolliePaymentProvider::class, Mockery::mock(MolliePaymentProvider::class, function ($mock) {
            $mock->shouldReceive('process')
                ->once()
                ->with(Mockery::on(function (PaymentInterface $arg) {

                    $contains = function ($needle, $haystack) {
                        return strpos($haystack, $needle) !== false;
                    };

                    $descr = $arg->getDescription();
                    return $arg->getCurrency() == "EUR"
                        && $arg->getTotalAmount() == 1000.00
                        && $contains($this->event->code, $descr)
                        && $contains($this->data["voornaam"], $descr)
                        && $contains($this->data["achternaam"], $descr);
                }))
                ->andReturns(redirect("https://mollie-backend"));
        }));

        $this->data["iDeal"] = '1';
        $response = $this->post('/register-participant', $this->data);

        // check db
        $this->assertDatabaseHas('users', $this->userData);
        $this->assertDatabaseHas('participants', $this->participantData);

        // redirect
        $response->assertStatus(302);
        $response->assertRedirect("https://mollie-backend");
    }


    /**
     * Tests the participants registration without iDeal
     */
    public function testParticipantRegistrationWithoutIDeal()
    {
        Mail::fake();

        $this->instance(MolliePaymentProvider::class, Mockery::mock(MolliePaymentProvider::class, function ($mock) {
            $mock->shouldNotReceive('process');
        }));

        $this->data["iDeal"] = 0;

        $response = $this->post('/register-participant', $this->data);

        // check db
        $this->assertDatabaseHas('users', $this->userData);
        $this->assertDatabaseHas('participants', $this->participantData);

        // Check output
        $response->assertStatus(200);
        $response->assertViewIs('registration.participantStored');
        $response->assertSee("€ " . $this->event->prijs);
        $response->assertSee("NL68 TRIO 0198 4197 83");
        $response->assertSee("naam deelnemer + deze kampcode: " . $this->event->code);
        $response->assertSee(Participant::INCOME_DESCRIPTION_TABLE[$this->data["inkomen"]]);
    }
}
