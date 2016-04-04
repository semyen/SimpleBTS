<?php

namespace OroAcademic\Bundle\AccountBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class OroAcademicSimpleBTSBundleInstaller implements Installation
{
    /**
     * @inheritdoc
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Tables generation **/
        $this->createOroAcademicSbtsIssueTable($schema);
        $this->createOroAcademicSbtsIssueCollaboratorsTable($schema);
        $this->createOroAcademicSbtsIssuePriorityTable($schema);
        $this->createOroAcademicSbtsIssuePriorityTranslationTable($schema);
        $this->createOroAcademicSbtsIssueRelationTable($schema);
        $this->createOroAcademicSbtsIssueResolutionTable($schema);
        $this->createOroAcademicSbtsIssueResolutionTranslationTable($schema);

        /** Foreign keys generation **/
        $this->addOroAcademicSbtsIssueForeignKeys($schema);
        $this->addOroAcademicSbtsIssueCollaboratorsForeignKeys($schema);
        $this->addOroAcademicSbtsIssueRelationForeignKeys($schema);
    }

    /**
     * Create oro_issue table
     *
     * @param Schema $schema
     */
    protected function createOroAcademicSbtsIssueTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('workflow_item_id', 'integer', ['notnull' => false]);
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);
        $table->addColumn('assignee_id', 'integer', ['notnull' => false]);
        $table->addColumn('workflow_step_id', 'integer', ['notnull' => false]);
        $table->addColumn('parent_id', 'integer', ['notnull' => false]);
        $table->addColumn('owner_id', 'integer', ['notnull' => false]);
        $table->addColumn('issue_resolution_id', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('reporter_id', 'integer', ['notnull' => false]);
        $table->addColumn('summary', 'string', ['length' => 255]);
        $table->addColumn('code', 'string', ['length' => 50]);
        $table->addColumn('description', 'text', []);
        $table->addColumn('created', 'datetime', []);
        $table->addColumn('updated', 'datetime', []);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['code'], 'UNIQ_2C20F97D77153098');
        $table->addUniqueIndex(['workflow_item_id'], 'UNIQ_2C20F97D1023C4EE');
        $table->addIndex(['issue_resolution_id'], 'IDX_2C20F97DC3CB7AE7', []);
        $table->addIndex(['reporter_id'], 'IDX_2C20F97DE1CFE6F5', []);
        $table->addIndex(['assignee_id'], 'IDX_2C20F97D59EC7D60', []);
        $table->addIndex(['parent_id'], 'IDX_2C20F97D727ACA70', []);
        $table->addIndex(['workflow_step_id'], 'IDX_2C20F97D71FE882C', []);
        $table->addIndex(['owner_id'], 'IDX_2C20F97D7E3C61F9', []);
        $table->addIndex(['organization_id'], 'IDX_2C20F97D32C8A3DE', []);
    }

    /**
     * Create oro_issue_collaborators table
     *
     * @param Schema $schema
     */
    protected function createOroAcademicSbtsIssueCollaboratorsTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue_collaborators');
        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('user_id', 'integer', []);
        $table->setPrimaryKey(['issue_id', 'user_id']);
        $table->addIndex(['issue_id'], 'IDX_B9A489F75E7AA58C', []);
        $table->addIndex(['user_id'], 'IDX_B9A489F7A76ED395', []);
    }

    /**
     * Create oro_issue_priority table
     *
     * @param Schema $schema
     */
    protected function createOroAcademicSbtsIssuePriorityTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue_priority');
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('label', 'string', ['length' => 255]);
        $table->setPrimaryKey(['name']);
        $table->addUniqueIndex(['label'], 'UNIQ_127D4E75EA750E8');
    }

    /**
     * Create oro_issue_priority_translation table
     *
     * @param Schema $schema
     */
    protected function createOroAcademicSbtsIssuePriorityTranslationTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue_priority_translation');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('foreign_key', 'string', ['length' => 16]);
        $table->addColumn('content', 'string', ['length' => 255]);
        $table->addColumn('locale', 'string', ['length' => 8]);
        $table->addColumn('object_class', 'string', ['length' => 255]);
        $table->addColumn('field', 'string', ['length' => 32]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['locale', 'object_class', 'field', 'foreign_key'], 'issue_priority_translation_index', []);
    }

    /**
     * Create oro_issue_relation table
     *
     * @param Schema $schema
     */
    protected function createOroAcademicSbtsIssueRelationTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue_relation');
        $table->addColumn('related_issue_id', 'integer', []);
        $table->addColumn('linked_issue_id', 'integer', []);
        $table->setPrimaryKey(['related_issue_id', 'linked_issue_id']);
        $table->addIndex(['related_issue_id'], 'IDX_1252D51BF8F9EB21', []);
        $table->addIndex(['linked_issue_id'], 'IDX_1252D51B307AEB53', []);
    }

    /**
     * Create oro_issue_resolut table
     *
     * @param Schema $schema
     */
    protected function createOroAcademicSbtsIssueResolutionTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue_resolut');
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('label', 'string', ['length' => 255]);
        $table->setPrimaryKey(['name']);
        $table->addUniqueIndex(['label'], 'UNIQ_6B5FB2EFEA750E8');
    }

    /**
     * Create oro_issue_resolut_translation table
     *
     * @param Schema $schema
     */
    protected function createOroAcademicSbtsIssueResolutionTranslationTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue_resolut_translation');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('foreign_key', 'string', ['length' => 16]);
        $table->addColumn('content', 'string', ['length' => 255]);
        $table->addColumn('locale', 'string', ['length' => 8]);
        $table->addColumn('object_class', 'string', ['length' => 255]);
        $table->addColumn('field', 'string', ['length' => 32]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['locale', 'object_class', 'field', 'foreign_key'], 'issue_resolution_translation_index', []);
    }

    /**
     * Add oro_issue foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOroAcademicSbtsIssueForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_issue');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_workflow_item'),
            ['workflow_item_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_organization'),
            ['organization_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['assignee_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_workflow_step'),
            ['workflow_step_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue'),
            ['parent_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['owner_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue_resolut'),
            ['issue_resolution_id'],
            ['name'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['reporter_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
    }

    /**
     * Add oro_issue_collaborators foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOroAcademicSbtsIssueCollaboratorsForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_issue_collaborators');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue'),
            ['issue_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['user_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
    }

    /**
     * Add oro_issue_relation foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOroAcademicSbtsIssueRelationForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_issue_relation');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue'),
            ['linked_issue_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue'),
            ['related_issue_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
    }
}
