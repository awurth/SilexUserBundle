<?php

namespace AWurth\SilexUser\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Base User class.
 *
 * @author Alexis Wurth <alexis.wurth57@gmail.com>
 *
 * @ORM\MappedSuperclass
 */
abstract class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=30, unique=true)
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    protected $password;

    /**
     * @var string
     */
    protected $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", nullable=true)
     */
    protected $salt;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled = false;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    protected $roles = [];

    /**
     * @var string
     *
     * @ORM\Column(name="confirmation_token", type="string", unique=true, nullable=true)
     */
    protected $confirmationToken;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addConstraint(new UniqueEntity([
            'fields' => 'username',
            'message' => 'silex_user.username.already_used'
        ]));
        $metadata->addConstraint(new UniqueEntity([
            'fields' => 'email',
            'message' => 'silex_user.email.already_used'
        ]));

        $metadata->addPropertyConstraint('username', new NotBlank(['message' => 'silex_user.username.blank']));
        $metadata->addPropertyConstraint('username', new Length([
            'min' => 3,
            'max' => 20,
            'minMessage' => 'silex_user.username.short',
            'maxMessage' => 'silex_user.username.long'
        ]));
        $metadata->addPropertyConstraint('username', new Regex([
            'pattern' => '/^[a-z0-9._-]+$/i',
            'message' => 'silex_user.username.invalid'
        ]));

        $metadata->addPropertyConstraint('email', new NotBlank(['message' => 'silex_user.username.blank']));
        $metadata->addPropertyConstraint('email', new Length([
            'max' => 100,
            'maxMessage' => 'silex_user.email.long'
        ]));
        $metadata->addPropertyConstraint('email', new Email(['message' => 'silex_user.email.invalid']));


        $metadata->addPropertyConstraint('password', new NotBlank(['message' => 'silex_user.password.blank']));
        $metadata->addPropertyConstraint('password', new Length([
            'min' => 8,
            'max' => 32,
            'minMessage' => 'silex_user.password.short',
            'maxMessage' => 'silex_user.password.long'
        ]));
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * {@inheritdoc}
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (bool) $enabled;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * {@inheritdoc}
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * {@inheritdoc}
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * {@inheritdoc}
     */
    public function setConfirmationToken($token)
    {
        $this->confirmationToken = $token;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }
}
