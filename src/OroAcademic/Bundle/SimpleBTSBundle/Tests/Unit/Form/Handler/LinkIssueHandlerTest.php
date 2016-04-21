<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Form\Handler;

use OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue;
use OroAcademic\Bundle\SimpleBTSBundle\Form\Handler\LinkIssueHandler;
use Symfony\Component\HttpFoundation\Request;

class LinkIssueHandlerTest extends \PHPUnit_Framework_TestCase
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
     * @var LinkIssueHandler
     */
    private $handler;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $entityManager;

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

        $relatedIssue = new Issue();

        $relatedIssueField =
            $this->getMockBuilder('Symfony\Component\Form\Form')
                ->disableOriginalConstructor()
                ->getMock()
        ;

        $relatedIssueField->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($relatedIssue))
        ;

        $this->form
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('relatedIssue'))
            ->will($this->returnValue($relatedIssueField))
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

        $this->issue = new Issue();

        $this->handler = new LinkIssueHandler(
            $this->form,
            $this->request,
            $this->entityManager
        );
    }

    /**
     * @param string $method
     * @dataProvider methodsData
     */
    public function testProcessData($method)
    {
        $this->request->setMethod($method);

        $this->form->expects($this->once())
            ->method('submit')
            ->with($this->request);

        $this->form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->assertTrue($this->handler->process($this->issue));
    }

    public function methodsData()
    {
        return [
            ['POST']
        ];
    }
}
