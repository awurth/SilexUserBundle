<?php

namespace AWurth\SilexUser\Entity;

use Doctrine\ORM\EntityRepository;
use Silex\Application;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserManager
{
    /**
     * @var Application
     */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->app['orm.em']->getRepository($this->app['silex_user.user_class']);
    }

    /**
     * Deletes a user.
     *
     * @param UserInterface $user
     */
    public function deleteUser(UserInterface $user)
    {
        $em = $this->app['orm.em'];

        $em->remove($user);
        $em->flush();
    }

    /**
     * Finds one user by the given criteria.
     *
     * @param array $criteria
     *
     * @return null|UserInterface
     */
    public function findUserBy(array $criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    /**
     * Finds a user by its email.
     *
     * @param string $email
     *
     * @return null|UserInterface
     */
    public function findUserByEmail($email)
    {
        return $this->findUserBy(array('email' => $email));
    }

    /**
     * Finds a user by its username.
     *
     * @param string $username
     *
     * @return null|UserInterface
     */
    public function findUserByUsername($username)
    {
        return $this->findUserBy(array('username' => $username));
    }

    /**
     * Finds a user by its username or email.
     *
     * @param string $usernameOrEmail
     *
     * @return null|UserInterface
     */
    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        if (preg_match('/^.+\@\S+\.\S+$/', $usernameOrEmail)) {
            return $this->findUserByEmail($usernameOrEmail);
        }

        return $this->findUserByUsername($usernameOrEmail);
    }

    /**
     * Returns a collection with all user instances.
     *
     * @return array
     */
    public function findUsers()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * Updates a user password if a plain password is set.
     *
     * @param UserInterface $user
     */
    public function updatePassword(UserInterface $user)
    {
        $plainPassword = $user->getPlainPassword();

        if (0 === strlen($plainPassword)) {
            return;
        }

        /** @var PasswordEncoderInterface $encoder */
        $encoder = $this->app['security.encoder_factory']->getEncoder($user);

        if ($encoder instanceof BCryptPasswordEncoder) {
            $user->setSalt(null);
        } else {
            $user->setSalt(rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '='));
        }

        $user->setPassword($encoder->encodePassword($plainPassword, $user->getSalt()));
        $user->eraseCredentials();
    }

    /**
     * Updates a user.
     *
     * @param UserInterface $user
     * @param bool $flush
     */
    public function updateUser(UserInterface $user, $flush = true)
    {
        $this->updatePassword($user);

        $em = $this->app['orm.em'];

        $em->persist($user);
        if ($flush) {
            $em->flush();
        }
    }
}
