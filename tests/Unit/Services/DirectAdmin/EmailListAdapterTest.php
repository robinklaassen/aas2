<?php

declare(strict_types=1);

namespace Tests\Unit\Services\DirectAdmin;

use App\Services\DirectAdmin\Contracts\Client;
use App\Services\DirectAdmin\EmailListAdapter;
use App\Services\DirectAdmin\ValueObjects\Command;
use App\Services\DirectAdmin\ValueObjects\EmailList;
use App\Services\DirectAdmin\ValueObjects\EmailListSubscribers;
use Hamcrest\Core\IsEqual;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\CompositeExpectation;
use Mockery\MockInterface;

final class EmailListAdapterTest extends MockeryTestCase
{
    private EmailListAdapter $subject;

    private MockInterface|Client $client;

    private EmailList $emailList;

    protected function setUp(): void
    {
        $this->client = Mockery::mock(Client::class);
        $this->subject = new EmailListAdapter($this->client);
        $this->emailList = new EmailList(':listname:', ':domain:');
    }

    public function testRemove(): void
    {
        $toRemove = new EmailListSubscribers($this->emailList, [':email1:', ':email2:']);

        $this->expectsCommand(
            command: Command::create(EmailListAdapter::CMD)
                ->asPost([
                    'name' => $this->emailList->name(),
                    'domain' => $this->emailList->domain(),
                    'action' => 'delete_subscriber',
                    'type' => 'list',
                    'select0' => ':email1:',
                    'select1' => ':email2:',
                ]),
            asJson: true
        );

        $this->subject->removeEmailListSubscribers($toRemove);
    }

    public function testAdd(): void
    {
        $toAdd = new EmailListSubscribers($this->emailList, [':email1:', ':email2:']);

        $this->expectsCommand(
            command: Command::create(EmailListAdapter::CMD)
                ->asPost([
                    'name' => $this->emailList->name(),
                    'domain' => $this->emailList->domain(),
                    'action' => 'add',
                    'type' => 'list',
                    'email' => ':email1:,:email2:',
                ]),
            asJson: true
        );

        $this->subject->addEmailListSubscribers($toAdd);
    }

    public function testGet(): void
    {
        $this->expectsCommand(
            command: Command::create(EmailListAdapter::CMD)
                ->asPost([
                    'name' => $this->emailList->name(),
                    'domain' => $this->emailList->domain(),
                    'action' => 'view',
                    'type' => 'list',
                ]),
            asJson: false
        )->andReturn('s%30=joeri%40anderwijs%2Enl&s%31=robin%40anderwijs%2Enl&s%32=vincent%40anderwijs%2Enl&d%30=piet%40anderwijs%2Enl');

        $result = $this->subject->getEmailListSubscribers($this->emailList);

        self::assertSame($this->emailList, $result->list());
        self::assertSame(['joeri@anderwijs.nl', 'robin@anderwijs.nl', 'vincent@anderwijs.nl'], $result->subscribers());
    }

    public function testSet(): void
    {
        $toSet = new EmailListSubscribers($this->emailList, ['piet@anderwijs.nl', 'vincent@anderwijs.nl', 'robin@anderwijs.nl']);

        $this->expectsCommand(
            command: Command::create(EmailListAdapter::CMD)
                ->asPost([
                    'name' => $this->emailList->name(),
                    'domain' => $this->emailList->domain(),
                    'action' => 'view',
                    'type' => 'list',
                ]),
            asJson: false
        )->andReturn('s%30=joeri%40anderwijs%2Enl&s%31=robin%40anderwijs%2Enl&s%32=vincent%40anderwijs%2Enl&d%30=piet%40anderwijs%2Enl');

        $this->expectsCommand(
            command: Command::create(EmailListAdapter::CMD)
                ->asPost([
                    'name' => $this->emailList->name(),
                    'domain' => $this->emailList->domain(),
                    'action' => 'delete_subscriber',
                    'type' => 'list',
                    'select0' => 'joeri@anderwijs.nl',
                ]),
            asJson: true
        );

        $this->expectsCommand(
            command: Command::create(EmailListAdapter::CMD)
                ->asPost([
                    'name' => $this->emailList->name(),
                    'domain' => $this->emailList->domain(),
                    'action' => 'add',
                    'type' => 'list',
                    'email' => 'piet@anderwijs.nl',
                ]),
            asJson: true
        );

        $this->subject->setEmailListSubscribers($toSet);
    }

    public function testSetDoesntDoAnythingForTheSameAddresses(): void
    {
        $toSet = new EmailListSubscribers($this->emailList, ['piet@anderwijs.nl', 'vincent@anderwijs.nl', 'robin@anderwijs.nl']);

        $this->expectsCommand(
            command: Command::create(EmailListAdapter::CMD)
                ->asPost([
                    'name' => $this->emailList->name(),
                    'domain' => $this->emailList->domain(),
                    'action' => 'view',
                    'type' => 'list',
                ]),
            asJson: false
        )->andReturn('s%30=piet%40anderwijs%2Enl&s%31=vincent%40anderwijs%2Enl&s%32=robin%40anderwijs%2Enl');

        $this->subject->setEmailListSubscribers($toSet);
    }

    private function expectsCommand(Command $command, bool $asJson): CompositeExpectation
    {
        return $this->client
            ->expects($asJson ? 'executeJson' : 'executeQueryString')
            ->with(IsEqual::equalTo($command));
    }
}
