<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Migrations\Data\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Oro\Bundle\DashboardBundle\Migrations\Data\ORM\AbstractDashboardFixture;

/**
 * @codeCoverageIgnore
 */
class LoadDashboardData extends AbstractDashboardFixture implements DependentFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return ['Oro\Bundle\UserBundle\Migrations\Data\ORM\LoadAdminUserData'];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $dashboard = $this->findAdminDashboardModel($manager, 'main');

        if ($dashboard) {
            $dashboard
                ->addWidget($this->createWidgetModel('active_issues', [0, 10]))
                ->addWidget($this->createWidgetModel('issues_by_status', [1, 10]))
            ;

            $manager->flush();
        }
    }
}
