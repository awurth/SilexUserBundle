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

use DateTime;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

interface UserInterface extends AdvancedUserInterface
{
    const ROLE_DEFAULT = 'ROLE_USER';

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
     * Gets the plain password.
     *
     * @return string
     */
    public function getPlainPassword();

    /**
     * Sets the plain password.
     *
     * @param string $password
     *
     * @return self
     */
    public function setPlainPassword($password);

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
     * Gets the last login date.
     *
     * @return DateTime
     */
    public function getLastLogin();

    /**
     * Sets the last login date.
     *
     * @param DateTime|null $date
     *
     * @return self
     */
    public function setLastLogin(DateTime $date = null);

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

    /**
     * Checks if a user has the given role.
     *
     * @param string $role
     *
     * @return bool
     */
    public function hasRole($role);

    /**
     * Adds a role to the user.
     *
     * @param string $role
     *
     * @return self
     */
    public function addRole($role);

    /**
     * Removes a role from the user.
     *
     * @param string $role
     *
     * @return self
     */
    public function removeRole($role);

    /**
     * Gets the confirmation token.
     *
     * @return string
     */
    public function getConfirmationToken();

    /**
     * Sets the confirmation token.
     *
     * @param string $token
     *
     * @return self
     */
    public function setConfirmationToken($token);
}
