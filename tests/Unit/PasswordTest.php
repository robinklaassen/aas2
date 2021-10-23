<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    public function testGeneratePassword()
    {
        $password = User::generatePassword();
        $this->assertEquals(10, strlen($password));
    }
}
