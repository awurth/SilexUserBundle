<?php

namespace AWurth\SilexUser\Entity;

interface UserManagerInterface
{
    /**
     * Creates an empty user instance.
     *
     * @return UserInterface
     */
    public function createUser();

    /**
     * Deletes a user.
     *
     * @param UserInterface $user
     */
    public function deleteUser(UserInterface $user);

    /**
     * Finds one user by the given criteria.
     *
     * @param array $criteria
     *
     * @return null|UserInterface
     */
    public function findUserBy(array $criteria);

    /**
     * Finds a user by its email.
     *
     * @param string $email
     *
     * @return null|UserInterface
     */
    public function findUserByEmail($email);

    /**
     * Finds a user by its username.
     *
     * @param string $username
     *
     * @return null|UserInterface
     */
    public function findUserByUsername($username);

    /**
     * Finds a user by its username or email.
     *
     * @param string $usernameOrEmail
     *
     * @return null|UserInterface
     */
    public function findUserByUsernameOrEmail($usernameOrEmail);

    /**
     * Returns a collection with all user instances.
     *
     * @return array
     */
    public function findUsers();

    /**
     * Updates a user password if a plain password is set.
     *
     * @param UserInterface $user
     */
    public function updatePassword(UserInterface $user);

    /**
     * Updates a user.
     *
     * @param UserInterface $user
     */
    public function updateUser(UserInterface $user);
}
