<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Entity;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\Repository\IssueRepository;

/**
 * @dbIsolation
 */
class IssueRepositoryTest extends WebTestCase
{
    /**
     * @var IssueRepository
     */
    protected $repository;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    protected function setUp()
    {
        $this->initClient([], $this->generateBasicAuthHeader());

        $this->loadFixtures([
            'Oro\Bundle\WorkflowBundle\Tests\Functional\DataFixtures\LoadWorkflowAwareEntities',
            'OroAcademic\Bundle\SimpleBTSBundle\Tests\Functional\DataFixtures\LoadUserData',
            'OroAcademic\Bundle\SimpleBTSBundle\Tests\Functional\DataFixtures\LoadIssueData'
        ]);

        $this->entityManager = $this->getContainer()->get('doctrine')->getEntityManager();

        $this->repository = $this->entityManager->getRepository('OroAcademicSimpleBTSBundle:Issue');
    }

    protected function tearDown()
    {
        unset($this->entityManager);
        unset($this->repository);
    }

    public function testGetIssueByStatus()
    {
        $aclHelper = $this->getContainer()->get('oro_security.acl_helper');
        $workflowSteps = $this->entityManager->getRepository('OroWorkflowBundle:WorkflowStep')->findAll();
        $issueList = $this->repository->getIssueByStatus($aclHelper, $workflowSteps);

        $this->assertTrue(!empty($issueList));
        $this->assertTrue(!empty($issueList['open']));
        $this->assertCount(5, $issueList);
    }
}
