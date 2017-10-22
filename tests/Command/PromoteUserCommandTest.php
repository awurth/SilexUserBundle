<?php

namespace AWurth\Silex\User\Tests\Command;

use AWurth\Silex\User\Command\PromoteUserCommand;
use PHPUnit\Framework\TestCase;
use Pimple\Container;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class PromoteUserCommandTest extends TestCase
{
    public function testExecute()
    {
        $commandTester = $this->createCommandTester($this->getContainer('user', 'role', false));
        $exitCode = $commandTester->execute([
            'username' => 'user',
            'role' => 'role'
        ], [
            'decorated' => false,
            'interactive' => false
        ]);

        $this->assertSame(0, $exitCode, 'Returns 0 in case of success');
        $this->assertRegExp('/Role "role" has been added to user "user"/', $commandTester->getDisplay());
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
            ->will($this->returnValue('role'));

        $application->getHelperSet()->set($helper, 'question');

        $commandTester = $this->createCommandTester($this->getContainer('user', 'role', false), $application);
        $exitCode = $commandTester->execute([], [
            'decorated' => false,
            'interactive' => true
        ]);

        $this->assertSame(0, $exitCode, 'Returns 0 in case of success');
        $this->assertRegExp('/Role "role" has been added to user "user"/', $commandTester->getDisplay());
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

        $command = new PromoteUserCommand($container);

        $application->add($command);

        return new CommandTester($application->find('silex-user:promote'));
    }

    /**
     * @param string $username
     * @param string $role
     * @param bool   $super
     *
     * @return mixed
     */
    private function getContainer($username, $role, $super)
    {
        $container = $this->getMockBuilder('Pimple\Container')->getMock();

        $manipulator = $this->getMockBuilder('AWurth\Silex\User\Util\UserManipulator')
            ->disableOriginalConstructor()
            ->getMock();

        if ($super) {
            $manipulator
                ->expects($this->once())
                ->method('promote')
                ->with($username)
                ->will($this->returnValue(true))
            ;
        } else {
            $manipulator
                ->expects($this->once())
                ->method('addRole')
                ->with($username, $role)
                ->will($this->returnValue(true))
            ;
        }

        $container
            ->expects($this->once())
            ->method('offsetGet')
            ->with('silex_user.util.user_manipulator')
            ->will($this->returnValue($manipulator));

        return $container;
    }
}
