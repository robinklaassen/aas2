<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;

class PasswordTest extends TestCase
{
    public function testGeneratePassword()
    {
        $password = User::generatePassword();
        $this->assertEquals(10, strlen($password));
    }
}
