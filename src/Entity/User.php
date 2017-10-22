<?php

/*
 * This file is part of the awurth/silex-user package.
 *
 * (c) Alexis Wurth <awurth.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AWurth\Silex\User\Entity;

use AWurth\Silex\User\Model\User as BaseUser;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Base User entity class.
 *
 * @author Alexis Wurth <awurth.dev@gmail.com>
 *
 * @ORM\MappedSuperclass
 */
abstract class User extends BaseUser
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
     * @ORM\Column(name="username", type="string", length=180, unique=true)
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=180, unique=true)
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
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    protected $salt;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled = false;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    protected $roles = [];

    /**
     * @var string
     *
     * @ORM\Column(name="confirmation_token", type="string", length=180, unique=true, nullable=true)
     */
    protected $confirmationToken;

    /**
     * {@inheritdoc}
     */
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

        parent::loadValidatorMetadata($metadata);
    }
}
