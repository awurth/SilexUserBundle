<?php

namespace AWurth\Silex\User\Tests\Command;

use AWurth\Silex\User\Command\ChangePasswordCommand;
use PHPUnit\Framework\TestCase;
use Pimple\Container;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ChangePasswordCommandTest extends TestCase
{
    public function testExecute()
    {
        $commandTester = $this->createCommandTester($this->getContainer('user', 'pass'));
        $exitCode = $commandTester->execute([
            'username' => 'user',
            'password' => 'pass'
        ], [
            'decorated' => false,
            'interactive' => false
        ]);

        $this->assertSame(0, $exitCode, 'Returns 0 in case of success');
        $this->assertRegExp('/Changed password for user user/', $commandTester->getDisplay());
    }

    public function testExecuteInteractiveWithQuestionHelper()
    {
        $application = new Application();

        $helper = $this->getMockBuilder('Symfony\Component\Console\Helper\QuestionHelper')
            ->setMethods(['ask'])
            ->getMock();

        $helper->expects($this->at(0))
            ->method('ask')
            ->will($this->returnValue('user'));
        $helper->expects($this->at(1))
            ->method('ask')
            ->will($this->returnValue('pass'));

        $application->getHelperSet()->set($helper, 'question');

        $commandTester = $this->createCommandTester($this->getContainer('user', 'pass'), $application);
        $exitCode = $commandTester->execute([], [
            'decorated' => false,
            'interactive' => true
        ]);

        $this->assertSame(0, $exitCode, 'Returns 0 in case of success');
        $this->assertRegExp('/Changed password for user user/', $commandTester->getDisplay());
    }

    /**
     * @param Container        $container
     * @param Application|null $application
     *
     * @return CommandTester
     */
    private function createCommandTester(Container $container, Application $application = null)
    {
        if (null === $application) {
            $application = new Application();
        }

        $application->setAutoExit(false);

        $command = new ChangePasswordCommand($container);

        $application->add($command);

        return new CommandTester($application->find('silex-user:change-password'));
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return mixed
     */
    private function getContainer($username, $password)
    {
        $container = $this->getMockBuilder('Pimple\Container')->getMock();

        $manipulator = $this->getMockBuilder('AWurth\Silex\User\Util\UserManipulator')
            ->disableOriginalConstructor()
            ->getMock();

        $manipulator
            ->expects($this->once())
            ->method('changePassword')
            ->with($username, $password)
        ;

        $container
            ->expects($this->once())
            ->method('offsetGet')
            ->with('silex_user.util.user_manipulator')
            ->will($this->returnValue($manipulator));

        return $container;
    }
}
