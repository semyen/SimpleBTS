<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\EmailBundle\Tests\Unit\ReflectionUtil;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\IssuePriority;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\IssueResolution;
use OroAcademic\Bundle\SimpleBTSBundle\Form\Type\IssueType;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class IssueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Issue
     */
    public function getIssue()
    {
        return new Issue();
    }

    public function testIdGetter()
    {
        $entity = new Issue();
        ReflectionUtil::setId($entity, 1);
        $this->assertEquals(1, $entity->getId());
    }

    public function testTaggableIdGetter()
    {
        $entity = new Issue();
        ReflectionUtil::setId($entity, 1);
        $this->assertEquals(1, $entity->getTaggableId());
    }

    /**
     * @dataProvider propertiesDataProvider
     * @param string $property
     * @param mixed  $value
     */
    public function testSettersAndGetters($property, $value)
    {
        $obj = $this->getIssue();

        $accessor = PropertyAccess::createPropertyAccessor();
        $accessor->setValue($obj, $property, $value);
        $this->assertEquals($value, $accessor->getValue($obj, $property));
    }

    /**
     * @return array
     */
    public function propertiesDataProvider()
    {
        return [
            ['code', 'ORO-1'],
            ['summary', 'Test Summary'],
            ['description', 'Test Description'],
            ['type', IssueType::STORY],
            ['parent', $this->getIssue()],
            ['priority', new IssuePriority()],
            ['resolution', new IssueResolution()],
            ['tags', new ArrayCollection()],
            ['reporter', new User()],
            ['assignee', new User()],
            ['owner', new User()],
            ['organization', new Organization()],
            ['workflowItem', new WorkflowItem()],
            ['workflowStep', new WorkflowStep()],
            ['createdAt', new \DateTime('now', new \DateTimeZone('UTC'))],
            ['updatedAt', new \DateTime('now', new \DateTimeZone('UTC'))],
        ];
    }

    public function testConstructor()
    {
        $issue = $this->getIssue();

        $this->assertInstanceOf(ArrayCollection::class, $issue->getTags());
        $this->assertInstanceOf(ArrayCollection::class, $issue->getRelatedIssues());
        $this->assertInstanceOf(ArrayCollection::class, $issue->getCollaborators());
        $this->assertInstanceOf(ArrayCollection::class, $issue->getChildren());
    }

    public function testRelatedIssueAddAndRemoveFunctions()
    {
        $issue = $this->getIssue();
        $relatedIssue = $this->getIssue();

        $this->assertCount(0, $issue->getRelatedIssues());

        $issue->addRelatedIssue($relatedIssue);
        $this->assertCount(1, $issue->getRelatedIssues());

        $issue->removeRelatedIssue($relatedIssue);
        $this->assertCount(0, $issue->getRelatedIssues());
    }

    public function testCollaboratorAddAndRemoveFunctions()
    {
        $issue = $this->getIssue();
        $user = new User();
        $user->setId(1);

        $this->assertCount(0, $issue->getCollaborators());

        $issue->addCollaborator($user);
        $this->assertCount(1, $issue->getCollaborators());

        $issue->removeCollaborator($user);
        $this->assertCount(0, $issue->getCollaborators());
    }

    public function testIsStory()
    {
        $issue = $this->getIssue();
        $type = IssueType::STORY;

        $issue->setType($type);
        $this->assertTrue($issue->isStory());
    }

    public function testIsSubTask()
    {
        $issue = $this->getIssue();
        $type = IssueType::SUB_TASK;

        $issue->setType($type);
        $this->assertTrue($issue->isSubTask());
    }
}
