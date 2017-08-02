<?php

namespace AWurth\SilexUser\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

interface UserInterface extends AdvancedUserInterface
{
    /**
     * Returns the user unique id.
     *
     * @return int
     */
    public function getId();

    /**
     * Sets the username.
     *
     * @param string $username
     *
     * @return self
     */
    public function setUsername($username);

    /**
     * Gets the email.
     *
     * @return string
     */
    public function getEmail();

    /**
     * Sets the email.
     *
     * @param string $email
     *
     * @return self
     */
    public function setEmail($email);

    /**
     * Sets the hashed password.
     *
     * @param string $password
     *
     * @return self
     */
    public function setPassword($password);

    /**
     * Sets the salt.
     *
     * @param string|null $salt
     */
    public function setSalt($salt);

    /**
     * @param bool $enabled
     *
     * @return self
     */
    public function setEnabled($enabled);

    /**
     * Sets the roles of the user.
     *
     * This overwrites any previous roles.
     *
     * @param array $roles
     *
     * @return self
     */
    public function setRoles(array $roles);
}
