<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\ImportExport\TemplateFixture;

use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\IssuePriority;

class IssuePriorityFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'OroAcademic\Bundle\SimpleBTSBundle\Entity\IssuePriority';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData(IssuePriority::MAJOR);
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new IssuePriority();
    }

    /**
     * @param string  $key
     * @param IssuePriority $entity
     */
    public function fillEntityData($key, $entity)
    {
        switch ($key) {
            case IssuePriority::BLOCKER:
                $entity
                    ->setName(IssuePriority::BLOCKER)
                    ->setLabel(ucfirst(IssuePriority::BLOCKER))
                    ->setPriorityOrder(10);
                return;
            case IssuePriority::CRITICAL:
                $entity
                    ->setName(IssuePriority::CRITICAL)
                    ->setLabel(ucfirst(IssuePriority::CRITICAL))
                    ->setPriorityOrder(20);
                return;
            case IssuePriority::MAJOR:
                $entity
                    ->setName(IssuePriority::MAJOR)
                    ->setLabel(ucfirst(IssuePriority::MAJOR))
                    ->setPriorityOrder(30);
                return;
            case IssuePriority::MINOR:
                $entity
                    ->setName(IssuePriority::MINOR)
                    ->setLabel(ucfirst(IssuePriority::MINOR))
                    ->setPriorityOrder(40);
                return;
            case IssuePriority::TRIVIAL:
                $entity
                    ->setName(IssuePriority::TRIVIAL)
                    ->setLabel(ucfirst(IssuePriority::TRIVIAL))
                    ->setPriorityOrder(50);
                return;
        }

        parent::fillEntityData($key, $entity);
    }
}
