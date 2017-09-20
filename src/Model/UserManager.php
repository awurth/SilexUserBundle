<?php

/*
 * This file is part of the awurth/silex-user package.
 *
 * (c) Alexis Wurth <awurth.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AWurth\SilexUser\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserManager implements UserManagerInterface
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * Constructor.
     *
     * @param ObjectManager           $objectManager
     * @param EncoderFactoryInterface $encoderFactory
     * @param string                  $class
     */
    public function __construct(ObjectManager $objectManager, EncoderFactoryInterface $encoderFactory, $class)
    {
        $this->objectManager = $objectManager;
        $this->encoderFactory = $encoderFactory;
        $this->class = $class;
    }

    /**
     * Gets the Doctrine repository for the User class.
     *
     * @return ObjectRepository
     */
    protected function getRepository()
    {
        return $this->objectManager->getRepository($this->getClass());
    }

    /**
     * {@inheritdoc}
     */
    public function createUser()
    {
        $class = $this->getClass();

        return new $class();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteUser(UserInterface $user)
    {
        $this->objectManager->remove($user);
        $this->objectManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function findUserBy(array $criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByEmail($email)
    {
        return $this->findUserBy(array('email' => $email));
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByUsername($username)
    {
        return $this->findUserBy(array('username' => $username));
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        if (preg_match('/^.+\@\S+\.\S+$/', $usernameOrEmail)) {
            return $this->findUserByEmail($usernameOrEmail);
        }

        return $this->findUserByUsername($usernameOrEmail);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByConfirmationToken($token)
    {
        return $this->findUserBy(['confirmationToken' => $token]);
    }

    /**
     * {@inheritdoc}
     */
    public function findUsers()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function updatePassword(UserInterface $user)
    {
        $plainPassword = $user->getPlainPassword();

        if (0 === strlen($plainPassword)) {
            return;
        }

        $encoder = $this->encoderFactory->getEncoder($user);
        if ($encoder instanceof BCryptPasswordEncoder) {
            $user->setSalt(null);
        } else {
            $user->setSalt(rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '='));
        }

        $user->setPassword($encoder->encodePassword($plainPassword, $user->getSalt()));
        $user->eraseCredentials();
    }

    /**
     * {@inheritdoc}
     */
    public function updateUser(UserInterface $user, $flush = true)
    {
        $this->updatePassword($user);

        $this->objectManager->persist($user);
        if ($flush) {
            $this->objectManager->flush();
        }
    }
}
