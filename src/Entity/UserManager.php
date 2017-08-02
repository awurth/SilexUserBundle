<?php

namespace AWurth\SilexUser\Entity;

use Doctrine\ORM\EntityRepository;
use Silex\Application;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserManager implements UserManagerInterface
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
     * {@inheritdoc}
     */
    public function deleteUser(UserInterface $user)
    {
        $em = $this->app['orm.em'];

        $em->remove($user);
        $em->flush();
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
    public function findUsers()
    {
        return $this->getRepository()->findAll();
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
     * {@inheritdoc}
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
