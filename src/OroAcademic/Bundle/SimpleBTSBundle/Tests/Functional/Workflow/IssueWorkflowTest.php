<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Functional\Workflow;

use Oro\Bundle\WorkflowBundle\Model\WorkflowManager;
use OroAcademic\Bundle\SimpleBTSBundle\Tests\Functional\Controller\IssueControllerTest;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @dbIsolation
 * @dbReindex
 */
class IssueWorkflowTest extends WebTestCase
{
    /**
     * @var IssueControllerTest
     */
    private $issueController;

    /**
     * @var WorkflowManager
     */
    private $workflowManager;

    /**
     * @var array
     */
    private $workflowSteps = ['start_progress', 'stop_progress', 'resolve', 'reopen', 'close'];

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->initClient([], $this->generateBasicAuthHeader());

        $this->loadFixtures([
            'OroAcademic\Bundle\SimpleBTSBundle\Tests\Functional\DataFixtures\LoadUserData',
            'OroAcademic\Bundle\SimpleBTSBundle\Tests\Functional\DataFixtures\LoadIssueData'
        ]);

        $this->issueController = new IssueControllerTest();
        $this->issueController->setUp();

        $this->entityManager = $this->getContainer()->get('doctrine')->getEntityManager();
        $this->workflowManager = $this->getContainer()->get('oro_workflow.manager');
    }

    public function testWorkflow()
    {
        //$this->issueController->testCreate();

        $issue = $this->getReference('issue_1');

        $workflowItem = $this->workflowManager->getWorkflowItemByEntity($issue);

        $this->assertEquals('open', $workflowItem->getCurrentStep()->getName());
        $this->assertCount(3, $this->workflowManager->getTransitionsByWorkflowItem($workflowItem));

        $this->workflowManager->transit($workflowItem, $this->workflowSteps[0]);

        $this->assertEquals('in_progress', $workflowItem->getCurrentStep()->getName());
        $this->assertCount(3, $this->workflowManager->getTransitionsByWorkflowItem($workflowItem));

        $this->workflowManager->transit($workflowItem, $this->workflowSteps[1]);

        $this->assertEquals('open', $workflowItem->getCurrentStep()->getName());
        $this->assertCount(3, $this->workflowManager->getTransitionsByWorkflowItem($workflowItem));

        $this->workflowManager->transit($workflowItem, $this->workflowSteps[0]);

        $this->assertEquals('in_progress', $workflowItem->getCurrentStep()->getName());
        $this->assertCount(3, $this->workflowManager->getTransitionsByWorkflowItem($workflowItem));

        $this->workflowManager->transit($workflowItem, $this->workflowSteps[2]);

        $this->assertEquals('resolved', $workflowItem->getCurrentStep()->getName());
        $this->assertCount(2, $this->workflowManager->getTransitionsByWorkflowItem($workflowItem));

        $this->workflowManager->transit($workflowItem, $this->workflowSteps[3]);

        $this->assertEquals('reopened', $workflowItem->getCurrentStep()->getName());
        $this->assertCount(3, $this->workflowManager->getTransitionsByWorkflowItem($workflowItem));

        $this->workflowManager->transit($workflowItem, $this->workflowSteps[4]);

        $this->assertEquals('closed', $workflowItem->getCurrentStep()->getName());
        $this->assertCount(1, $this->workflowManager->getTransitionsByWorkflowItem($workflowItem));
    }
}
