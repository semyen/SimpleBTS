<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Functional\Controller;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @dbIsolation
 * @dbReindex
 */
class DashboardControllerTest extends WebTestCase
{
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
    }

    public function testIndex()
    {
        $this->client->request(
            'GET',
            $this->getUrl('oro_academic_sbts_dashboard_issue_by_status', ['widget' => 'issues_by_status'])
        );

        $result = $this->client->getResponse();

        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('issues-by-status-widget-content', $result->getContent());
    }
}
