<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\ImportExport\Strategy;

use Oro\Bundle\ImportExportBundle\Strategy\Import\AbstractImportStrategy;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue;

class IssueAddStrategy extends AbstractImportStrategy
{
    /**
     * {@inheritdoc}
     */
    public function process($entity)
    {
        $this->assertEnvironment($entity);

        /** @var Issue $entity */
        $entity = $this->beforeProcessEntity($entity);
        $entity = $this->afterProcessEntity($entity);

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    protected function afterProcessEntity($entity)
    {
        /** @var Issue $entity */
        $entity = parent::afterProcessEntity($entity);

        return $entity;
    }
}
