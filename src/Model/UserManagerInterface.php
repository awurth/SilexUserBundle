<?php

namespace AWurth\SilexUser\Model;

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
     * @return UserInterface|null
     */
    public function findUserBy(array $criteria);

    /**
     * Finds a user by its email.
     *
     * @param string $email
     *
     * @return UserInterface|null
     */
    public function findUserByEmail($email);

    /**
     * Finds a user by its username.
     *
     * @param string $username
     *
     * @return UserInterface|null
     */
    public function findUserByUsername($username);

    /**
     * Finds a user by its username or email.
     *
     * @param string $usernameOrEmail
     *
     * @return UserInterface|null
     */
    public function findUserByUsernameOrEmail($usernameOrEmail);

    /**
     * Finds a user by its confirmation token.
     *
     * @param string $token
     *
     * @return UserInterface|null
     */
    public function findUserByConfirmationToken($token);

    /**
     * Returns a collection with all user instances.
     *
     * @return array
     */
    public function findUsers();

    /**
     * Returns the user's fully qualified class name.
     *
     * @return string
     */
    public function getClass();

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
