<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\EventListener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;

use Oro\Bundle\OrganizationBundle\Entity\Organization;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue;
use OroAcademic\Bundle\SimpleBTSBundle\EventListener\IssueEventListener;

class IssueEventListenerTest extends \PHPUnit_Framework_TestCase
{
    const ORGANIZATION = 'ORO';

    /** @var Issue */
    protected $issue;

    /** @var IssueEventListener */
    protected $issueEventListener;

    protected $em;

    protected $uow;

    public function setUp()
    {
        $this->uow = $this->getMockBuilder('Doctrine\ORM\UnitOfWork')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $user = $this
            ->getMockBuilder('Oro\Bundle\UserBundle\Entity\User')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $tokenInterface = $this
            ->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\TokenInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $tokenInterface
            ->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue($user))
        ;

        $tokenStorage = $this
            ->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $tokenStorage
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($tokenInterface))
        ;

        $container = $this->getMock('Symfony\Component\DependencyInjection\Container', array('get'));
        $container
            ->expects($this->any())
            ->method('get')
            ->with('security.token_storage')
            ->will($this->returnValue($tokenStorage))
        ;

        $this->issueEventListener = new IssueEventListener($container);

        $organization = $this
            ->getMockBuilder(Organization::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $organization
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(self::ORGANIZATION))
        ;

        $this->issue = (new Issue())
            ->setSummary('Test Summary')
            ->setOrganization($organization)
        ;
    }

    public function testOnFlush()
    {
        $onFlushEventArgs = $this->prepareIssueEventListenerOnFlush([$this->issue]);
        $this->assertEmpty($this->issueEventListener->getIssues());

        $this->issueEventListener->onFlush($onFlushEventArgs);
        $this->assertNotEmpty($this->issueEventListener->getIssues());
    }

    /**
     * @depends testOnFlush
     */
    public function testPostFlush()
    {
        $onFlushEventArgs = $this->prepareIssueEventListenerOnFlush([$this->issue]);
        $postFlushEventArgs = $this->preparePostFlushEvent();

        $this->issueEventListener->onFlush($onFlushEventArgs);
        $this->assertNotEmpty($this->issueEventListener->getIssues());

        $refObject = new \ReflectionObject($this->issue);
        $refProperty = $refObject->getProperty('id');
        $refProperty->setAccessible(true);
        $refProperty->setValue($this->issue, 1);

        $this->issueEventListener->postFlush($postFlushEventArgs);
        $this->assertNotEmpty($this->issueEventListener->getIssues());

        $this->assertEquals($this->issueEventListener->getIssues()[0]->getCode(), self::ORGANIZATION . '-1');
    }

    /**
     * @param $issues
     * @return \PHPUnit_Framework_MockObject_MockObject|OnFlushEventArgs
     */
    protected function prepareIssueEventListenerOnFlush($issues)
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|OnFlushEventArgs $onFlushEventArgs */
        $onFlushEventArgs = $this->getMockBuilder('Doctrine\ORM\Event\OnFlushEventArgs')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->uow
            ->expects($this->once())
            ->method('getScheduledEntityInsertions')
            ->will($this->returnValue($issues))
        ;

        $this->em->expects($this->once())
            ->method('getUnitOfWork')
            ->will($this->returnValue($this->uow))
        ;

        $onFlushEventArgs
            ->expects($this->any())
            ->method('getEntityManager')
            ->will($this->returnValue($this->em))
        ;


        return $onFlushEventArgs;
    }

    /**
     * @return PostFlushEventArgs|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function preparePostFlushEvent()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|PostFlushEventArgs $postFlushEventArgs */
        $postFlushEventArgs = $this->getMockBuilder('Doctrine\ORM\Event\PostFlushEventArgs')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $postFlushEventArgs
            ->expects($this->any())
            ->method('getEntityManager')
            ->will($this->returnValue($this->em))
        ;
        $this->em
            ->expects($this->once())
            ->method('persist')
        ;
        $this->em
            ->expects($this->once())
            ->method('flush')
        ;

        return $postFlushEventArgs;
    }
}
