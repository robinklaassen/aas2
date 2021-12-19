<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\ContactFormMessage;
use App\Services\Recaptcha\RecaptchaValidator;
use App\ValueObjects\RecaptchaResult\Failure;
use App\ValueObjects\RecaptchaResult\Success;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Tests\TestCase;

final class ContactFormControllerTest extends TestCase
{
    private const CONTENT = [
        'email' => 'email@domain.nl',
        'name' => ':name:',
        'phone' => '0612345678',
        'message' => ':message:',
        'recaptcha' => ':recaptcha:',
    ];

    /**
     * @var RecaptchaValidator|Mockery\MockInterface
     */
    private $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = Mockery::mock(RecaptchaValidator::class);

        $this->app->bind(RecaptchaValidator::class, function () {
            return $this->validator;
        });
    }

    public function testContactFormFormValidation(): void
    {
        $this
            ->post('api/contact-form')
            ->assertSessionHasErrors(['email', 'name', 'phone', 'message', 'recaptcha']);
    }

    public function testContactFormFormValidationPhone(): void
    {
        $this
            ->post('api/contact-form', array_merge(self::CONTENT, [
                'phone' => '061234567',
            ]))
            ->assertSessionHasErrors(['phone']);
    }

    public function testContactFormFormValidationEmail(): void
    {
        $this
            ->post('api/contact-form', array_merge(self::CONTENT, [
                'email' => 'test',
            ]))
            ->assertSessionHasErrors(['email']);
    }

    public function testContactFormValidationRecaptcha(): void
    {
        $this->validator
            ->expects('validate')
            ->with(':recaptcha:')
            ->andReturn(Failure::unknown());

        $this
            ->post('api/contact-form', self::CONTENT)
            ->assertSessionHasErrors(['recaptcha']);
    }

    public function testContactFormSendsMail(): void
    {
        Mail::fake();

        $this->validator
            ->expects('validate')
            ->with(':recaptcha:')
            ->andReturn(new Success());

        $this
            ->post('api/contact-form', self::CONTENT)
            ->assertSessionHasNoErrors()
            ->assertStatus(200)
            ->assertJson([
                'messages' => ['Bericht succesvol verstuurd.'],
            ]);

        Mail::assertSent(ContactFormMessage::class);
    }
}
