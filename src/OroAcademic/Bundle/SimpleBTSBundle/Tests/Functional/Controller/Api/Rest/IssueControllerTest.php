<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Functional\Controller\Api\Rest;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\IssuePriority;
use OroAcademic\Bundle\SimpleBTSBundle\Form\Type\IssueType;

/**
 * @dbIsolation
 * @dbReindex
 */
class IssueControllerTest extends WebTestCase
{
    /**
     * @var array
     */
    protected $issue;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->initClient([], $this->generateWsseAuthHeader());

        $entityManager = $this->getContainer()->get('doctrine')->getEntityManager();

        if (!isset($this->issue['summary'])) {
            $this->issue['summary'] = 'Test Summary';
        }
        if (!isset($this->issue['description'])) {
            $this->issue['description'] = 'Test Description';
        }

        if (!isset($this->issue['owner'])) {
            $this->issue['owner'] = $entityManager
                ->getRepository('OroUserBundle:User')
                ->findOneBy(['username' => self::USER_NAME])
                ->getId();
        }
        if (!isset($this->issue['reporter'])) {
            $this->issue['reporter'] = $entityManager
                ->getRepository('OroUserBundle:User')
                ->findOneBy(['username' => self::USER_NAME])
                ->getId();
        }

        if (!isset($this->issue['priority'])) {
            $this->issue['priority'] = $entityManager
                ->getRepository('OroAcademicSimpleBTSBundle:IssuePriority')
                ->findOneBy(['name' => IssuePriority::MAJOR])
                ->getName();
        }

        if (!isset($this->issue['type'])) {
            $this->issue['type'] = IssueType::STORY;
        }
    }

    public function testCreate()
    {
        $this->client->request('POST', $this->getUrl('oro_academic_sbts_issue_post_api'), $this->issue);

        $issue = $this->getJsonResponseContent($this->client->getResponse(), 201);

        $this->assertArrayHasKey('id', $issue);

        $this->assertNotEmpty($issue['id']);

        return $issue['id'];
    }

    /**
     * @depends testCreate
     */
    public function testCget()
    {
        $this->client->request('GET', $this->getUrl('oro_academic_sbts_issue_get_apis'));
        $issues = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertCount(1, $issues);
    }

    /**
     * @depends testCreate
     */
    public function testCgetFiltering()
    {
        $url = $this->getUrl('oro_academic_sbts_issue_get_apis');

        $reporterId  = $this->issue['reporter'];
        $ownerId  = $this->issue['owner'];
        $date     = '2016-04-20T11:20:08+03:00';

        $this->client->request('GET', $url . '?createdAt>' . $date);
        $this->assertCount(1, $this->getJsonResponseContent($this->client->getResponse(), 200));

        $this->client->request('GET', $url . '?createdAt<' . $date);
        $this->assertEmpty($this->getJsonResponseContent($this->client->getResponse(), 200));

        $this->client->request('GET', $url . '?reporterId=' . $reporterId);
        $this->assertCount(1, $this->getJsonResponseContent($this->client->getResponse(), 200));

        $this->client->request('GET', $url . '?reporterId=' . ($reporterId + 1));
        $this->assertEmpty($this->getJsonResponseContent($this->client->getResponse(), 200));

        $this->client->request('GET', $url . '?ownerId=' . $ownerId);
        $this->assertCount(1, $this->getJsonResponseContent($this->client->getResponse(), 200));

        $this->client->request('GET', $url . '?ownerId=' . ($ownerId + 1));
        $this->assertEmpty($this->getJsonResponseContent($this->client->getResponse(), 200));
    }

    /**
     * @depends testCreate
     * @param integer $id
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function testGet($id)
    {
        $this->client->request('GET', $this->getUrl('oro_academic_sbts_issue_get_api', ['id' => $id]));
        $issue = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertEquals($this->issue['summary'], $issue['summary']);
    }

    /**
     * @depends testCreate
     * @param integer $id
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function testPut($id)
    {
        $updatedIssue = array_merge($this->issue, ['summary' => 'Updated summary']);

        $this->client->request(
            'PUT',
            $this->getUrl('oro_academic_sbts_issue_put_api', ['id' => $id]),
            $updatedIssue
        );
        $result = $this->client->getResponse();
        $this->assertEmptyResponseStatusCodeEquals($result, 204);

        $this->client->request('GET', $this->getUrl('oro_academic_sbts_issue_get_api', ['id' => $id]));

        $issue = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertEquals('Updated summary', $issue['summary']);
        $this->assertEquals($updatedIssue['summary'], $issue['summary']);
    }

    /**
     * @depends testCreate
     * @param integer $id
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function testDelete($id)
    {
        $this->client->request('DELETE', $this->getUrl('oro_academic_sbts_issue_delete_api', ['id' => $id]));
        $result = $this->client->getResponse();
        $this->assertEmptyResponseStatusCodeEquals($result, 204);

        $this->client->request('GET', $this->getUrl('oro_academic_sbts_issue_get_api', ['id' => $id]));
        $result = $this->client->getResponse();
        $this->assertJsonResponseStatusCodeEquals($result, 404);
    }
}
