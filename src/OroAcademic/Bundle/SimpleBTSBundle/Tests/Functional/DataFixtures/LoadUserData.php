<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Functional\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements ContainerAwareInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Organization
     */
    protected $organization;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->entityManager = $manager;
        $this->organization = $manager->getRepository('OroOrganizationBundle:Organization')->getFirst();

        $user1 = $this->createUser('Richard', 'Bradley');

        $this->setReference('user_1', $user1);

        $this->entityManager->flush();
    }

    protected function createUser($firstName, $lastName)
    {
        $user = new User();
        $user->setOrganization($this->organization);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setUsername(strtolower($firstName . '.' . $lastName));
        $user->setPassword(strtolower($firstName . '.' . $lastName));
        $user->setEmail(strtolower($firstName . '_' . $lastName . '@example.com'));

        $this->entityManager->persist($user);

        return $user;
    }
}
