<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Form\Handler;

use OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue;
use OroAcademic\Bundle\SimpleBTSBundle\Form\Handler\IssueHandler;
use Symfony\Component\HttpFoundation\Request;

class IssueHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $form;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var IssueHandler
     */
    private $handler;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $entityManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $entityRoutingHelper;

    /**
     * @var Issue
     */
    protected $issue;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->form =
            $this->getMockBuilder('Symfony\Component\Form\Form')
                ->disableOriginalConstructor()
                ->getMock()
        ;

        $this->request = new Request();

        $this->entityManager =
            $this->getMockBuilder('Doctrine\ORM\EntityManager')
                ->disableOriginalConstructor()
                ->getMock()
        ;

        $issueRepository =
            $this->getMockBuilder('OroAcademic\Bundle\SimpleBTSBundle\Entity\Repository\IssueRepository')
                ->disableOriginalConstructor()
                ->getMock();

        $this->entityManager
            ->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($issueRepository))
        ;

        $this->entityRoutingHelper =
            $this->getMockBuilder('Oro\Bundle\EntityBundle\Tools\EntityRoutingHelper')
                ->disableOriginalConstructor()
                ->getMock()
        ;

        $this->issue = new Issue();
        $this->handler = new IssueHandler(
            $this->form,
            $this->request,
            $this->entityManager,
            $this->entityRoutingHelper
        );

        $tagManager = $this->getMockBuilder('Oro\Bundle\TagBundle\Entity\TagManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->handler->setTagManager($tagManager);
    }

    /**
     * @param string $method
     * @dataProvider methodsData
     */
    public function testProcessData($method)
    {
        $this->request->setMethod($method);

        $this->form->expects($this->once())
            ->method('setData')
            ->with($this->issue);

        $this->form->expects($this->once())
            ->method('submit')
            ->with($this->request);

        $this->form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->assertTrue($this->handler->process($this->issue));
        $this->assertInstanceOf('DateTime', $this->issue->getCreatedAt());
        $this->assertInstanceOf('DateTime', $this->issue->getUpdatedAt());
    }

    public function methodsData()
    {
        return [
            ['POST'],
            ['PUT']
        ];
    }
}
