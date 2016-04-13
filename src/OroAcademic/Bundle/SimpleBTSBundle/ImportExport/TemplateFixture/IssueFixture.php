<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\ImportExport\TemplateFixture;

use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\IssuePriority;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\IssueResolution;
use OroAcademic\Bundle\SimpleBTSBundle\Form\Type\IssueType;

class IssueFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData('Story');
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new Issue();
    }

    /**
     * @param string  $key
     * @param Issue $entity
     */
    public function fillEntityData($key, $entity)
    {
        $userRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\UserBundle\Entity\User');
        $organizationRepo = $this->templateManager
            ->getEntityRepository('Oro\Bundle\OrganizationBundle\Entity\Organization');
        $issuePriorityRepo = $this->templateManager
            ->getEntityRepository('OroAcademic\Bundle\SimpleBTSBundle\Entity\IssuePriority');
        $issueResolutionRepo = $this->templateManager
            ->getEntityRepository('OroAcademic\Bundle\SimpleBTSBundle\Entity\IssueResolution');

        switch ($key) {
            case 'Bug':
                $entity
                    ->setCode('ORO-1')
                    ->setSummary('Bug Summary')
                    ->setDescription('Bug Description')
                    ->setType(IssueType::BUG)
                    ->setPriority($issuePriorityRepo->getEntity(IssuePriority::MINOR))
                    ->setResolution($issueResolutionRepo->getEntity(IssueResolution::FIXED))
                    ->setReporter($userRepo->getEntity('John Doo'))
                    ->setAssignee($userRepo->getEntity('John Doo'))
                    ->setParent(null)
                    ->setCreatedAt(new \DateTime('1973-03-07'))
                    ->setUpdatedAt(new \DateTime('1973-03-07'))
                    ->setOwner($userRepo->getEntity('John Doo'))
                    ->setOrganization($organizationRepo->getEntity('default'));
                return;
            case 'Story':
                $entity
                    ->setCode('ORO-2')
                    ->setSummary('Story Summary')
                    ->setDescription('Story Description')
                    ->setType(IssueType::STORY)
                    ->setPriority($issuePriorityRepo->getEntity(IssuePriority::CRITICAL))
                    ->setResolution(null)
                    ->setReporter($userRepo->getEntity('John Doo'))
                    ->setAssignee(null)
                    ->setParent(null)
                    ->setCreatedAt(new \DateTime('1973-03-07'))
                    ->setUpdatedAt(new \DateTime('1973-03-07'))
                    ->setOwner($userRepo->getEntity('John Doo'))
                    ->setOrganization($organizationRepo->getEntity('default'));
                return;
            case 'Sub-Task':
                $entity
                    ->setCode('ORO-3')
                    ->setSummary('Sub-Task Summary')
                    ->setDescription('Sub-Task Description')
                    ->setType(IssueType::SUB_TASK)
                    ->setPriority($issuePriorityRepo->getEntity(IssuePriority::MINOR))
                    ->setResolution($issueResolutionRepo->getEntity(IssueResolution::DONE))
                    ->setReporter($userRepo->getEntity('John Doo'))
                    ->setAssignee($userRepo->getEntity('John Doo'))
                    ->setParent($this->getEntity('Story'))
                    ->setCreatedAt(new \DateTime('1973-03-07'))
                    ->setUpdatedAt(new \DateTime('1973-03-07'))
                    ->setOwner($userRepo->getEntity('John Doo'))
                    ->setOrganization($organizationRepo->getEntity('default'));
                return;
            case 'Task':
                $entity
                    ->setCode('ORO-4')
                    ->setSummary('Task Summary')
                    ->setDescription('Task Description')
                    ->setType(IssueType::TASK)
                    ->setPriority($issuePriorityRepo->getEntity(IssuePriority::MINOR))
                    ->setResolution($issueResolutionRepo->getEntity(IssueResolution::DONE))
                    ->setReporter($userRepo->getEntity('John Doo'))
                    ->setAssignee($userRepo->getEntity('John Doo'))
                    ->setParent(null)
                    ->setCreatedAt(new \DateTime('1973-03-07'))
                    ->setUpdatedAt(new \DateTime('1973-03-07'))
                    ->setOwner($userRepo->getEntity('John Doo'))
                    ->setOrganization($organizationRepo->getEntity('default'));
                return;
        }

        parent::fillEntityData($key, $entity);
    }
}
