<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    public function testGeneratePassword()
    {
        $password = User::generatePassword();
        $this->assertSame(10, strlen($password));
    }
}
