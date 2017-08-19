<?php

namespace AWurth\SilexUser\Mailer;

use AWurth\SilexUser\Entity\UserInterface;

interface MailerInterface
{
    /**
     * Sends an email to a user to confirm the account creation.
     *
     * @param UserInterface $user
     */
    public function sendConfirmationEmailMessage(UserInterface $user);
}
