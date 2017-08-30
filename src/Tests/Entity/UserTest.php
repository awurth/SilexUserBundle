<?php

namespace AWurth\SilexUser\Tests\Entity;

use AWurth\SilexUser\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUsername()
    {
        $user = $this->getUser();
        $this->assertNull($user->getUsername());

        $user->setUsername('awurth');
        $this->assertSame('awurth', $user->getUsername());
    }

    public function testEmail()
    {
        $user = $this->getUser();
        $this->assertNull($user->getEmail());

        $user->setEmail('awurth@awurth.fr');
        $this->assertSame('awurth@awurth.fr', $user->getEmail());
    }

    public function testPassword()
    {
        $user = $this->getUser();
        $this->assertNull($user->getPassword());

        $user->setPassword('my_password');
        $this->assertSame('my_password', $user->getPassword());
    }

    public function testPlainPassword()
    {
        $user = $this->getUser();
        $this->assertNull($user->getPlainPassword());

        $user->setPlainPassword('my_plain_password');
        $this->assertSame('my_plain_password', $user->getPlainPassword());
    }

    public function testSalt()
    {
        $user = $this->getUser();
        $this->assertNull($user->getSalt());

        $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');

        $user->setSalt($salt);
        $this->assertSame($salt, $user->getSalt());
    }

    public function testEnabled()
    {
        $user = $this->getUser();
        $this->assertFalse($user->isEnabled());

        $user->setEnabled(true);
        $this->assertTrue($user->isEnabled());
    }

    public function testConfirmationToken()
    {
        $user = $this->getUser();
        $this->assertNull($user->getConfirmationToken());

        $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');

        $user->setConfirmationToken($token);
        $this->assertSame($token, $user->getConfirmationToken());
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->getMockForAbstractClass(User::class);
    }
}
