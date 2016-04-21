<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Functional\Controller;

use Doctrine\ORM\EntityManager;

use OroAcademic\Bundle\SimpleBTSBundle\Entity\IssuePriority;
use OroAcademic\Bundle\SimpleBTSBundle\Form\Type\IssueType;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @dbIsolation
 * @dbReindex
 */
class IssueControllerTest extends WebTestCase
{
    /** @var array */
    protected $issue;

    /**
     * @var EntityManager
     */
    private $entityManager;

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

        $this->entityManager = $this->getContainer()->get('doctrine')->getEntityManager();
    }

    public function testCreate()
    {
        $crawler = $this->client->request('GET', $this->getUrl('oro_academic_sbts_issue_create'));

        /** @var \OroAcademic\Bundle\SimpleBTSBundle\Entity\IssuePriority $priority */
        $priority = $this->entityManager
            ->getRepository('OroAcademicSimpleBTSBundle:IssuePriority')
            ->findOneBy(['name' => IssuePriority::MAJOR]);

        $form = $crawler->filter('form[name=oro_academic_sbts_issue_form]')->filter('button[type=submit]')->form();
        $form['oro_academic_sbts_issue_form[summary]'] = 'New issue';
        $form['oro_academic_sbts_issue_form[description]'] = 'New description';
        $form['oro_academic_sbts_issue_form[type]'] = IssueType::STORY;
        $form['oro_academic_sbts_issue_form[priority]'] = $priority->getName();
        $form['oro_academic_sbts_issue_form[owner]'] = '1';

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains("Issue was successfully saved", $crawler->html());
    }

    /**
     * @depends testCreate
     */
    public function testView()
    {
        $issueId = $this->getReference('issue_1')->getId();

        $this->client->request(
            'GET',
            $this->getUrl('oro_academic_sbts_issue_view', ['id' => $issueId])
        );

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains($this->getReference('issue_1')->getCode(), $result->getContent());
    }

    /**
     * @depends testCreate
     */
    public function testCreateSubTask()
    {
        $parentIssueId = $this->getReference('issue_1')->getId();

        $crawler = $this->client->request(
            'GET',
            $this->getUrl('oro_academic_sbts_issue_create', ['id' => $parentIssueId]) . 'parentId=' . $parentIssueId
        );

        $priority = $this->entityManager
            ->getRepository('OroAcademicSimpleBTSBundle:IssuePriority')
            ->findOneBy(['name' => IssuePriority::MAJOR])
        ;

        $form = $crawler->filter('form[name=oro_academic_sbts_issue_form]')->filter('button[type=submit]')->form();
        $form['oro_academic_sbts_issue_form[summary]'] = 'Test Sub-Task #' . uniqid();
        $form['oro_academic_sbts_issue_form[description]'] = 'Test Description #' . uniqid();
        $form['oro_academic_sbts_issue_form[priority]'] = $priority->getName();
        $form['oro_academic_sbts_issue_form[type]'] = IssueType::SUB_TASK;
        $form['oro_academic_sbts_issue_form[owner]'] = '1';

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains("Issue was successfully saved", $crawler->html());
    }

    /**
     * @depends testView
     */
    public function testUpdate()
    {
        $issueId = $this->getReference('issue_1')->getId();
        $userId = $this->getReference('user_1')->getId();

        $crawler = $this->client->request(
            'GET',
            $this->getUrl('oro_academic_sbts_issue_update', ['id' => $issueId])
        );

        $form = $crawler->filter('form[name=oro_academic_sbts_issue_form]')->filter('button[type=submit]')->form();
        $form['oro_academic_sbts_issue_form[summary]'] = 'New Test Summary';
        $form['oro_academic_sbts_issue_form[assignee]'] = $userId;

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains("Issue was successfully saved", $crawler->html());
    }

    /**
     * @depends testUpdate
     */
    public function testIndex()
    {
        $this->client->request('GET', $this->getUrl('oro_academic_sbts_issue_index'));

        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('New Test Summary', $result->getContent());
    }
}
