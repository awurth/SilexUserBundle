<?php

namespace AWurth\Silex\User\Tests\Form\Type;

use AWurth\Silex\User\Form\Type\RegistrationFormType;
use AWurth\Silex\User\Tests\Model\TestUser;
use Symfony\Component\Form\Tests\Extension\Validator\Type\TypeTestCase;

class RegistrationFormTypeTest extends TypeTestCase
{
    public function testSubmit()
    {
        $user = new TestUser();

        $form = $this->factory->create(RegistrationFormType::class, $user);
        $formData = [
            'username' => 'bar',
            'email' => 'john@doe.com',
            'plainPassword' => [
                'password' => 'test',
                'confirm_password' => 'test'
            ]
        ];
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertSame($user, $form->getData());
        $this->assertSame('bar', $user->getUsername());
        $this->assertSame('john@doe.com', $user->getEmail());
        $this->assertSame('test', $user->getPlainPassword());
    }
}
