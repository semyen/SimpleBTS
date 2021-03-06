<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use OroAcademic\Bundle\SimpleBTSBundle\Entity\IssuePriority;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\IssueResolution;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue;

use Oro\Bundle\WorkflowBundle\Model\WorkflowManager;

/**
 * @codeCoverageIgnore
 */
class LoadIssueData extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
{
    const START_PROGRESS = 'start_progress';
    const STOP_PROGRESS = 'stop_progress';
    const RESOLVE = 'resolve';
    const REOPEN = 'reopen';
    const CLOSE = 'close';
    /**
     * @var WorkflowManager
     */
    protected $workflowManager;

    /**
     * @var array
     */
    private $types = [Issue::BUG, Issue::STORY, Issue::SUB_TASK, Issue::TASK];

    /**
     * @var array
     */
    private $priorities = [
        IssuePriority::BLOCKER,
        IssuePriority::CRITICAL,
        IssuePriority::MAJOR,
        IssuePriority::MINOR,
        IssuePriority::TRIVIAL
    ];

    /**
     * @var array
     */
    private $resolutions = [
        IssueResolution::FIXED,
        IssueResolution::WONT_FIX,
        IssueResolution::DUPLICATE,
        IssueResolution::INCOMPLETE,
        IssueResolution::CANNOT_REPRODUCE,
        IssueResolution::DONE,
        IssueResolution::WONT_DO,
        IssueResolution::REJECTED
    ];

    /**
     * @var array
     */
    private $workflowSteps = [self::START_PROGRESS, self::STOP_PROGRESS, self::RESOLVE, self::REOPEN, self::CLOSE];

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return ['OroAcademic\Bundle\SimpleBTSBundle\Migrations\Data\Demo\ORM\LoadUserData'];
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->workflowManager = $container->get('oro_workflow.manager');
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $issue = $this->createIssue($manager);
            if (!empty($issue) && $issue->isStory()) {
                $subTaskCount = rand(1, 5);
                for ($j = 0; $j < $subTaskCount; $j++) {
                    $this->createIssue($manager, Issue::SUB_TASK, $issue);
                }
            }
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @param string $type
     * @param Issue|null $parent
     * @return Issue|null
     */
    private function createIssue(ObjectManager $manager, $type = '', $parent = null)
    {
        $issue = new Issue();

        $type = !empty($type) ? $type : $this->getRandomType();
        $organization = $manager->getRepository('OroOrganizationBundle:Organization')->getFirst();
        $admin = $manager->getRepository('OroUserBundle:User')->findOneBy(['username' => 'admin']);
        $user = $manager->getRepository('OroUserBundle:User')->findOneBy(['username' => 'user']);
        $currentDate = new \DateTime('now', new \DateTimeZone('UTC'));

        if (empty($organization) || empty($admin) || empty($user)) {
            return null;
        }

        $priority = $this->getRandomPriority($manager);

        $issue
            ->setCode(uniqid())
            ->setSummary($this->getRandomSummary())
            ->setDescription($this->getRandomDescription())
            ->setPriority($priority)
            ->setType($type)
            ->setOwner($admin)
            ->setReporter($admin)
            ->setAssignee($user)
            ->setCreatedAt($currentDate)
            ->setUpdatedAt($currentDate)
            ->setOrganization($organization)
            ->addCollaborator($user)
        ;

        if (!empty($parent)) {
            $issue->setParent($parent);
        }

        $manager->persist($issue);
        $manager->flush($issue);

        return $this->setWorkflowStep($issue, $manager);
    }

    /**
     * @param Issue $issue
     * @param ObjectManager $manager
     * @return Issue
     * @throws \Exception
     */
    private function setWorkflowStep(Issue $issue, ObjectManager $manager)
    {
        $nextStepIdx = $this->getRandomWorkflowStepIndex();

        $workflowItem = $this->workflowManager->getWorkflowItemByEntity($issue);
        for ($i = 0; $i <= $nextStepIdx; $i++) {
            $this->workflowManager->transit($workflowItem, $this->workflowSteps[$i]);
        }

        if (in_array($workflowItem->getCurrentStep()->getName(), ['resolved', 'closed'])) {
            $issue->setResolution($this->getRandomResolution($manager));
        }

        return $issue;
    }

    /**
     * @return string
     */
    private function getRandomSummary()
    {
        $summary = 'Task Summary #' . uniqid();

        return $summary;
    }

    /**
     * @return string
     */
    private function getRandomDescription()
    {
        $description = 'Task Description #' . uniqid();

        return $description;
    }

    /**
     * @param ObjectManager $manager
     * @return IssuePriority
     */
    private function getRandomPriority(ObjectManager $manager)
    {
        $priority = $this->priorities[rand(0, count($this->priorities) - 1)];

        return $manager
            ->createQueryBuilder()
            ->select('t')
            ->from('OroAcademicSimpleBTSBundle:IssuePriority', 't')
            ->where('t.name = :priority')
            ->setParameter('priority', $priority)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    /**
     * @param ObjectManager $manager
     * @return IssueResolution
     */
    private function getRandomResolution(ObjectManager $manager)
    {
        $resolution = $this->resolutions[rand(0, count($this->resolutions) - 1)];

        return $manager
            ->createQueryBuilder()
            ->select('t')
            ->from('OroAcademicSimpleBTSBundle:IssueResolution', 't')
            ->where('t.name = :resolution')
            ->setParameter('resolution', $resolution)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    /**
     * @return string
     */
    private function getRandomType()
    {
        return $this->types[rand(0, count($this->types) - 1)];
    }

    /**
     * @return string
     */
    private function getRandomWorkflowStepIndex()
    {
        return rand(0, count($this->workflowSteps) - 1);
    }
}
