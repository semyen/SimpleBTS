<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\ImportExport\TemplateFixture;

use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\IssueResolution;

class IssueResolutionFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'OroAcademic\Bundle\SimpleBTSBundle\Entity\IssueResolution';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData(IssueResolution::DONE);
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new IssueResolution();
    }

    /**
     * @param string  $key
     * @param IssueResolution $entity
     */
    public function fillEntityData($key, $entity)
    {
        switch ($key) {
            case IssueResolution::CANNOT_REPRODUCE:
                $entity
                    ->setName(IssueResolution::CANNOT_REPRODUCE)
                    ->setLabel(ucfirst(IssueResolution::CANNOT_REPRODUCE));
                return;
            case IssueResolution::DONE:
                $entity
                    ->setName(IssueResolution::DONE)
                    ->setLabel(ucfirst(IssueResolution::DONE));
                return;
            case IssueResolution::DUPLICATE:
                $entity
                    ->setName(IssueResolution::DUPLICATE)
                    ->setLabel(ucfirst(IssueResolution::DUPLICATE));
                return;
            case IssueResolution::FIXED:
                $entity
                    ->setName(IssueResolution::FIXED)
                    ->setLabel(ucfirst(IssueResolution::FIXED));
                return;
            case IssueResolution::INCOMPLETE:
                $entity
                    ->setName(IssueResolution::INCOMPLETE)
                    ->setLabel(ucfirst(IssueResolution::INCOMPLETE));
                return;
            case IssueResolution::REJECTED:
                $entity
                    ->setName(IssueResolution::REJECTED)
                    ->setLabel(ucfirst(IssueResolution::REJECTED));
                return;
            case IssueResolution::WONT_DO:
                $entity
                    ->setName(IssueResolution::WONT_DO)
                    ->setLabel(ucfirst(IssueResolution::WONT_DO));
                return;
            case IssueResolution::WONT_FIX:
                $entity
                    ->setName(IssueResolution::WONT_FIX)
                    ->setLabel(ucfirst(IssueResolution::WONT_FIX));
                return;
        }

        parent::fillEntityData($key, $entity);
    }
}
