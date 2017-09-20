<?php

namespace AWurth\SilexUser\Tests\Model;

use AWurth\SilexUser\Model\UserManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserManagerTest extends TestCase
{
    const USER_CLASS = TestUser::class;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectManager;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $repository;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $userManager;

    public function setUp()
    {
        $class = $this->getMockBuilder(ClassMetadata::class)->getMock();
        $this->objectManager = $this->getMockBuilder(ObjectManager::class)->getMock();
        $this->repository = $this->getMockBuilder(ObjectRepository::class)->getMock();

        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(self::USER_CLASS))
            ->willReturn($this->repository);
        $this->objectManager->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::USER_CLASS))
            ->willReturn($class);
        $class->expects($this->any())->method('getName')->willReturn(static::USER_CLASS);

        $encoderFactory = $this->getMockBuilder(EncoderFactoryInterface::class)->getMock();

        $this->userManager = new UserManager($this->objectManager, $encoderFactory, static::USER_CLASS);
    }

    public function testCreateUser()
    {
        $user = $this->userManager->createUser();

        $this->assertInstanceOf(TestUser::class, $user);
        $this->assertEquals(new TestUser(), $user);
    }

    public function testDeleteUser()
    {
        $user = $this->getUser();
        $this->objectManager->expects($this->once())->method('remove')->with($this->equalTo($user));
        $this->objectManager->expects($this->once())->method('flush');

        $this->userManager->deleteUser($user);
    }

    public function testFindUserBy()
    {
        $criteria = ['foo' => 'bar'];
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo($criteria))
            ->willReturn(null);

        $this->userManager->findUserBy($criteria);
    }

    public function testFindUsers()
    {
        $this->repository->expects($this->once())->method('findAll')->willReturn([]);

        $this->userManager->findUsers();
    }
    
    public function testGetClass()
    {
        $this->assertSame(TestUser::class, $this->userManager->getClass());
    }

    public function testUpdateUser()
    {
        $user = $this->getUser();
        $this->objectManager->expects($this->once())->method('persist')->with($this->equalTo($user));
        $this->objectManager->expects($this->once())->method('flush');

        $this->userManager->updateUser($user);
    }

    /**
     * @return TestUser
     */
    protected function getUser()
    {
        return new TestUser();
    }
}
