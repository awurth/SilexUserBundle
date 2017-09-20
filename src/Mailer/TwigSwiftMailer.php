<?php

/*
 * This file is part of the awurth/silex-user package.
 *
 * (c) Alexis Wurth <awurth.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AWurth\SilexUser\Mailer;

use AWurth\SilexUser\Model\UserInterface;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig_Environment;

class TwigSwiftMailer implements MailerInterface
{
    /**
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var array
     */
    protected $parameters;

    public function __construct(Swift_Mailer $mailer, Twig_Environment $twig, UrlGeneratorInterface $router, array $parameters)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->router = $router;
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $url = $this->router->generate('silex_user.registration_confirm', [
            'token' => $user->getConfirmationToken()
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $context = [
            'user' => $user,
            'confirmationUrl' => $url
        ];

        $this->sendMessage('silex_user/registration/email.twig', $context, $this->parameters['from_email']['confirmation'], $user->getEmail());
    }

    /**
     * @param string $templateName
     * @param array $context
     * @param string|array $fromEmail
     * @param string $toEmail
     */
    protected function sendMessage($templateName, array $context, $fromEmail, $toEmail)
    {
        $template = $this->twig->load($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);

        $htmlBody = '';
        
        if ($template->hasBlock('body_html', $context)) {
            $htmlBody = $template->renderBlock('body_html', $context);
        }

        $message = new Swift_Message();
        $message->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail);

        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        $this->mailer->send($message);
    }
}
