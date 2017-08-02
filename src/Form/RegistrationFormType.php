<?php

namespace AWurth\SilexUser\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * User Registration Form Type.
 *
 * @author Alexis Wurth <alexis.wurth57@gmail.com>
 */
class RegistrationFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_name' => 'password',
                'second_name' => 'confirm_password',
                'first_options' => ['label' => 'silex_user.form.password'],
                'second_options' => ['label' => 'silex_user.form.password_confirmation'],
                'invalid_message' => 'silex_user.password.mismatch'
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'silex_user_registration';
    }
}
