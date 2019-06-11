<?php

namespace App\Tests\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\BlogPost;
use App\Entity\User;
use App\EventSubscriber\AuthoredEntitySubscriber;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AuthoredEntitySubscriberTest extends TestCase
{
    public function testConfiguration()
    {
        $result = AuthoredEntitySubscriber::getSubscribedEvents();

        $this->assertArrayHasKey(KernelEvents::VIEW, $result);
        $this->assertEquals(
            ['getAuthenticatedUser', EventPriorities::PRE_WRITE],
            $result[KernelEvents::VIEW]
        );
    }

    public function testSetAuthorCall()
    {
        $tokenStorageMock = $this->getTokenStorageMock();
        $eventMock = $this->getEventMock('POST', new BlogPost());

        (new AuthoredEntitySubscriber($tokenStorageMock))
            ->getAuthenticatedUser($eventMock);
    }

    /**
     * @return MockObject|TokenStorageInterface
     */
    private function getTokenStorageMock(): MockObject
    {
        $tokenMock = $this->getMockBuilder(TokenInterface::class)
            ->getMockForAbstractClass();
        $tokenMock->expects(self::once())
            ->method('getUser')
            ->willReturn(new User());

        /** @var MockObject|TokenStorageInterface $tokenStorageMock */
        $tokenStorageMock = $this->getMockBuilder(TokenStorageInterface::class)
            ->getMockForAbstractClass();

        $tokenStorageMock->expects(self::once())
            ->method('getToken')
            ->willReturn($tokenMock);
        return $tokenStorageMock;
    }

    /**
     * @param string $method
     * @param $controllerResult
     * @return MockObject|GetResponseForControllerResultEvent
     */
    private function getEventMock(string $method, $controllerResult): MockObject
    {
        $requestMock = $this->getMockBuilder(Request::class)
            ->getMock();
        $requestMock->expects(self::once())
            ->method('getMethod')
            ->willReturn($method);

        $eventMock = $this->getMockBuilder(GetResponseForControllerResultEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $eventMock->expects(self::once())
            ->method('getControllerResult')
            ->willReturn($controllerResult);

        $eventMock->expects(self::once())
            ->method('getRequest')
            ->willReturn($requestMock);

        return $eventMock;
    }
}
