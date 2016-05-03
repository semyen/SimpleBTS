<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\ImportExport\Strategy;

use Oro\Bundle\ImportExportBundle\Strategy\Import\ConfigurableAddOrReplaceStrategy;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue;

class IssueAddStrategy extends ConfigurableAddOrReplaceStrategy
{
    /**
     * {@inheritdoc}
     */
    protected function importExistingEntity(
        $entity,
        $existingEntity,
        $itemData = null,
        array $excludedFields = array()
    ) {
        parent::importExistingEntity($entity, $existingEntity, $itemData, $excludedFields);
    }

    /**
     * {@inheritdoc}
     */
    protected function beforeProcessEntity($entity)
    {
        /** @var Issue $entity */
        $entity->setCode(null);
        $entity->setWorkflowItem(null);
        $entity->setWorkflowStep(null);

        /** @var Issue $entity */
        $entity = parent::beforeProcessEntity($entity);

        // need to manually set empty types to skip merge from existing entities
        $itemData = $this->context->getValue('itemData');
        $this->context->setValue('itemData', $itemData);

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

    /**
     * {@inheritdoc}
     */
    protected function findEntityByIdentityValues($entityName, array $identityValues)
    {
        return parent::findEntityByIdentityValues($entityName, $identityValues);
    }
}
