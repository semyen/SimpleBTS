<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Migrations\Data\Demo\ORM;

use Oro\Bundle\UserBundle\Migrations\Data\ORM\LoadRolesData;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use Oro\Bundle\UserBundle\Entity\Role;
use Oro\Bundle\UserBundle\Entity\UserManager;
use Oro\Bundle\OrganizationBundle\Migrations\Data\ORM\LoadOrganizationAndBusinessUnitData;

class LoadUserData extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
{
    const DEFAULT_USERNAME = 'user';
    const DEFAULT_PASSWORD = 'user';
    const DEFAULT_EMAIL = 'user@example.com';

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            'Oro\Bundle\UserBundle\Migrations\Data\ORM\LoadRolesData',
            'Oro\Bundle\OrganizationBundle\Migrations\Data\ORM\LoadOrganizationAndBusinessUnitData',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->userManager = $container->get('oro_user.manager');
    }

    /**
     * Load default user
     *
     * @param ObjectManager $manager
     * @throws \RuntimeException
     */
    public function load(ObjectManager $manager)
    {
        $userRole = $manager->getRepository('OroUserBundle:Role')
            ->findOneBy(['role' => LoadRolesData::ROLE_USER]);

        if (!$userRole) {
            throw new \RuntimeException('User role should exist.');
        }

        if ($this->isUserWithRoleExist($manager, $userRole)) {
            return;
        }

        $businessUnit = $manager
            ->getRepository('OroOrganizationBundle:BusinessUnit')
            ->findOneBy(['name' => LoadOrganizationAndBusinessUnitData::MAIN_BUSINESS_UNIT]);

        $organization = $manager->getRepository('OroOrganizationBundle:Organization')->getFirst();

        $user = $this->userManager->createUser();

        $user
            ->setUsername(self::DEFAULT_USERNAME)
            ->setEmail(self::DEFAULT_EMAIL)
            ->setPlainPassword(md5(self::DEFAULT_PASSWORD))
            ->setFirstName('First Name')
            ->setLastName('Last Name')
            ->setEnabled(true)
            ->setOwner($businessUnit)
            ->addRole($userRole)
            ->addBusinessUnit($businessUnit)
            ->setOrganization($organization)
        ;

        $this->userManager->updateUser($user);
    }

    /**
     * @param ObjectManager $manager
     * @param Role $role
     * @return bool
     */
    protected function isUserWithRoleExist(ObjectManager $manager, Role $role)
    {
        return null !== $manager->getRepository('OroUserBundle:Role')->getFirstMatchedUser($role);
    }
}
