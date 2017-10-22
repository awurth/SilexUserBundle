<?php

namespace AWurth\Silex\User\Tests\Security;

use AWurth\Silex\User\Security\LoginManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginManagerTest extends TestCase
{
    public function testLogInUserWithRequestStack()
    {
        $loginManager = $this->createLoginManager();
        $loginManager->logInUser('main', $this->mockUser());
    }

    public function testLogInUserWithRememberMeAndRequestStack()
    {
        $response = $this->getMockBuilder('Symfony\Component\HttpFoundation\Response')->getMock();

        $loginManager = $this->createLoginManager($response);
        $loginManager->logInUser('main', $this->mockUser(), $response);
    }

    /**
     * @param Response|null $response
     *
     * @return LoginManager
     */
    protected function createLoginManager(Response $response = null)
    {
        $tokenStorage = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface')->getMock();

        $tokenStorage
            ->expects($this->once())
            ->method('setToken')
            ->with($this->isInstanceOf('Symfony\Component\Security\Core\Authentication\Token\TokenInterface'));

        $userChecker = $this->getMockBuilder('Symfony\Component\Security\Core\User\UserCheckerInterface')->getMock();
        $userChecker
            ->expects($this->once())
            ->method('checkPreAuth')
            ->with($this->isInstanceOf('AWurth\Silex\User\Model\UserInterface'));

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();

        $sessionStrategy = $this->getMockBuilder('Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface')->getMock();
        $sessionStrategy
            ->expects($this->once())
            ->method('onAuthentication')
            ->with($request, $this->isInstanceOf('Symfony\Component\Security\Core\Authentication\Token\TokenInterface'));

        $requestStack = $this->getMockBuilder('Symfony\Component\HttpFoundation\RequestStack')->getMock();
        $requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->will($this->returnValue($request));

        $rememberMe = null;
        if (null !== $response) {
            $rememberMe = $this->getMockBuilder('Symfony\Component\Security\Http\RememberMe\RememberMeServicesInterface')->getMock();
            $rememberMe
                ->expects($this->once())
                ->method('loginSuccess')
                ->with($request, $response, $this->isInstanceOf('Symfony\Component\Security\Core\Authentication\Token\TokenInterface'));
        }

        return new LoginManager($tokenStorage, $userChecker, $sessionStrategy, $requestStack, $rememberMe);
    }

    /**
     * @return mixed
     */
    protected function mockUser()
    {
        $user = $this->getMockBuilder('AWurth\Silex\User\Model\UserInterface')->getMock();
        $user
            ->expects($this->once())
            ->method('getRoles')
            ->will($this->returnValue(['ROLE_USER']));

        return $user;
    }
}
