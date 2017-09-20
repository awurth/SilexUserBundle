<?php

/*
 * This file is part of the awurth/silex-user package.
 *
 * (c) Alexis Wurth <awurth.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AWurth\SilexUser\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class DeactivateUserCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('silex-user:deactivate')
            ->setDescription('Deactivate a user')
            ->setDefinition([
                new InputArgument('username', InputArgument::REQUIRED, 'The username')
            ])
            ->setHelp(<<<'EOT'
The <info>silex-user:deactivate</info> command deactivates a user (will not be able to log in)

  <info>php %command.full_name% matthieu</info>
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');

        $manipulator = $this->container['silex_user.util.user_manipulator'];
        $manipulator->deactivate($username);

        $output->writeln(sprintf('User "%s" has been deactivated.', $username));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('username')) {
            $question = new Question('Please choose a username:');
            $question->setValidator(function ($username) {
                if (empty($username)) {
                    throw new \Exception('Username can not be empty');
                }

                return $username;
            });
            $answer = $this->getHelper('question')->ask($input, $output, $question);

            $input->setArgument('username', $answer);
        }
    }
}
