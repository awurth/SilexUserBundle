<?php

namespace AWurth\SilexUser\Tests\Entity;

use AWurth\SilexUser\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @var User
     */
    protected $user;

    public function setUp()
    {
        $this->user = $this->getMockForAbstractClass(User::class);
    }
    
    public function testUsername()
    {
        $this->assertNull($this->user->getUsername());

        $this->user->setUsername('awurth');
        $this->assertSame('awurth', $this->user->getUsername());
    }

    public function testEmail()
    {
        $this->assertNull($this->user->getEmail());

        $this->user->setEmail('awurth@awurth.fr');
        $this->assertSame('awurth@awurth.fr', $this->user->getEmail());
    }

    public function testPassword()
    {
        $this->assertNull($this->user->getPassword());

        $this->user->setPassword('my_password');
        $this->assertSame('my_password', $this->user->getPassword());
    }

    public function testPlainPassword()
    {
        $this->assertNull($this->user->getPlainPassword());

        $this->user->setPlainPassword('my_plain_password');
        $this->assertSame('my_plain_password', $this->user->getPlainPassword());
    }

    public function testSalt()
    {
        $this->assertNull($this->user->getSalt());

        $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');

        $this->user->setSalt($salt);
        $this->assertSame($salt, $this->user->getSalt());
    }

    public function testEnabled()
    {
        $this->assertFalse($this->user->isEnabled());

        $this->user->setEnabled(true);
        $this->assertTrue($this->user->isEnabled());
    }

    public function testConfirmationToken()
    {
        $this->assertNull($this->user->getConfirmationToken());

        $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');

        $this->user->setConfirmationToken($token);
        $this->assertSame($token, $this->user->getConfirmationToken());
    }

    public function testRoles()
    {
        $this->assertSame([User::ROLE_DEFAULT], $this->user->getRoles());

        $this->user->setRoles(['ROLE_ADMIN', 'ROLE_SUPER_ADMIN']);
        $this->assertSame(['ROLE_ADMIN', 'ROLE_SUPER_ADMIN', User::ROLE_DEFAULT], $this->user->getRoles());
    }
    
    public function testHasRole()
    {
        $defaultRole = User::ROLE_DEFAULT;
        $newRole = 'ROLE_ADMIN';
        $this->assertTrue($this->user->hasRole($defaultRole));
        $this->assertFalse($this->user->hasRole($newRole));

        $this->user->addRole($defaultRole);
        $this->assertTrue($this->user->hasRole($defaultRole));
        $this->user->addRole($newRole);
        $this->assertTrue($this->user->hasRole($newRole));

        $this->user->removeRole($defaultRole);
        $this->assertTrue($this->user->hasRole($defaultRole));
        $this->user->removeRole($newRole);
        $this->assertFalse($this->user->hasRole($newRole));
    }
}
