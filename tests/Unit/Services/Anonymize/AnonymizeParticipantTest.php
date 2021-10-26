<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Anonymize;

use App\Comment;
use App\Participant;
use App\Services\Anonymize\AnonymizeGeneratorInterface;
use App\Services\Anonymize\AnonymizeParticipant;
use App\Services\ObjectManager\ObjectManagerInterface;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

/**
 * Technically not a unit test because of `test_it_gets_participants_to_anonymize` hitting the database
 * But splitting this up was too much effort and code duplication for now
 */
class AnonymizeParticipantTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var ObjectManagerInterface|Mockery\LegacyMockInterface|Mockery\MockInterface
     */
    private $objectManager;

    /**
     * @var AnonymizeGeneratorInterface|Mockery\LegacyMockInterface|Mockery\MockInterface
     */
    private $generator;

    /**
     * @var AnonymizeParticipant
     */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->objectManager = Mockery::mock(ObjectManagerInterface::class);
        $this->generator = Mockery::mock(AnonymizeGeneratorInterface::class);

        $this->subject = new AnonymizeParticipant($this->generator, $this->objectManager);
    }

    public function testItAnonymizes()
    {
        $data = $this->fakeParticipantData();

        $fieldsToAnonymize = array_keys($data);

        $comment1 = new Comment();
        $comment2 = new Comment();
        $user = new User();
        $participant = new Participant();
        $participant->user = $user;
        $participant->fill($data);
        $participant->comments = new Collection([$comment1, $comment2]);

        $this->objectManager->expects('save')->with($participant);
        $this->objectManager->expects('forceDelete')->with($user);
        $this->objectManager->expects('forceDelete')->with($comment1);
        $this->objectManager->expects('forceDelete')->with($comment2);

        $this->generator->expects('firstname')->andReturn('test');
        $this->generator->expects('surname')->andReturn('test');
        $this->generator->expects('surnamePrefix')->andReturn('');
        $this->generator->expects('birthdate')->andReturn('1900-01-01');
        $this->generator->expects('address')->andReturn('');
        $this->generator->expects('zipcode')->andReturn('');
        $this->generator->expects('telephone')->times(3)->andReturn('');
        $this->generator->expects('email')->times(2)->andReturn('');
        $this->generator->expects('city')->andReturn('');

        $this->subject->anonymize($participant);

        foreach ($fieldsToAnonymize as $field) {
            self::assertNotSame($participant->{$field}, $data[$field]);
        }
        self::assertNotNull($participant->anonymized_at);
    }

    public function testItGetsParticipantsToAnonymize()
    {
        $participant = new Participant();
        $participant->fill($this->fakeParticipantData());
        $participant->save();
        $participant->events()->attach(13, [
            'datum_betaling' => '2009-12-01',
            'geplaatst' => 1,
        ]);

        $result = $this->subject->getParticipantsToAnonymize(new \DateTimeImmutable('2020-12-12T00:00:00Z'));

        $lastItem = $result[count($result) - 1];
        self::assertSame($participant->id, $lastItem->id);
    }

    public function testItGetsParticipantToAnonymize()
    {
        $participant = new Participant();
        $participant->fill($this->fakeParticipantData());
        $participant->anonymized_at = new \DateTimeImmutable();
        $participant->save();
        $participant->events()->attach(13, [
            'datum_betaling' => '2009-12-01',
            'geplaatst' => 1,
        ]);

        $result = $this->subject->getParticipantsToAnonymize(new \DateTimeImmutable('2020-12-12T00:00:00Z'));

        $lastItem = $result[count($result) - 1] ?? null;
        self::assertNotSame($participant->id, $lastItem !== null ? $lastItem->id : null);
    }

    private function fakeParticipantData(): array
    {
        return [
            'voornaam' => 'piet',
            'achternaam' => 'henk',
            'geboortedatum' => new Carbon('2010-01-01'),
            'postcode' => '1111BC',
            'adres' => 'teststraat',
            'telefoon_deelnemer' => '0612345678',
            'telefoon_ouder_vast' => '0612345678',
            'telefoon_ouder_mobiel' => '0612345678',
            'email_deelnemer' => 'test@test.nl',
            'email_ouder' => 'test@test.nl',
            'inkomen' => '2',
            'school' => 'Test school',
            'opmerkingen' => 'Test school',
        ];
    }
}
