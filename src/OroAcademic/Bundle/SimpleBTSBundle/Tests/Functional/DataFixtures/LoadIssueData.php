<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Functional\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

use OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\IssuePriority;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Oro\Bundle\EmailBundle\Entity\Email;
use Oro\Bundle\EmailBundle\Model\FolderType;
use Oro\Bundle\EmailBundle\Builder\EmailEntityBuilder;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;

class LoadIssueData extends AbstractFixture implements ContainerAwareInterface, DependentFixtureInterface
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var Organization */
    protected $organization;

    /** @var EmailEntityBuilder */
    protected $emailEntityBuilder;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->emailEntityBuilder = $container->get('oro_email.email.entity.builder');
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return ['OroAcademic\Bundle\SimpleBTSBundle\Tests\Functional\DataFixtures\LoadUserData'];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->entityManager = $manager;
        $this->organization = $manager->getRepository('OroOrganizationBundle:Organization')->getFirst();

        $issue1 = $this->createIssue(uniqid('ORO-'));

        $this->setReference('issue_1', $issue1);

        $this->entityManager->flush();
    }

    /**
     * @param string $code
     * @return Issue
     */
    protected function createIssue($code)
    {
        $user = $this->getReference('user_1');

        $priority = $this
            ->entityManager
            ->getRepository('OroAcademicSimpleBTSBundle:IssuePriority')
            ->findOneBy(['name' => IssuePriority::MAJOR])
        ;

        $issue = new Issue();
        $issue->setCode($code);
        $issue->setSummary('Test  Summary #' . uniqid());
        $issue->setDescription('Test Description #' . uniqid());
        $issue->setType(Issue::TASK);
        $issue->setPriority($priority);
        $issue->setReporter($user);
        $issue->setOwner($user);
        $issue->setCreatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
        $issue->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
        $issue->setOrganization($this->organization);
        $issue->addCollaborator($user);

        $this->entityManager->persist($issue);

        return $issue;
    }
}
