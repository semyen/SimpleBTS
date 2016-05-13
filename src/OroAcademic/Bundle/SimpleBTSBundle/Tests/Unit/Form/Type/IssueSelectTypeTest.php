<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Form\Type;

use OroAcademic\Bundle\SimpleBTSBundle\Form\Type\IssueSelectType;
use Symfony\Component\Form\Test\FormIntegrationTestCase;

class IssueSelectTypeTest extends FormIntegrationTestCase
{
    const NAME = 'oro_academic_sbts_issue_select';
    const PARENT_NAME = 'oro_entity_create_or_select_inline';
    const AUTOCOMPLETE_ALIAS = 'issues';
    const GRID_NAME = 'oro_academic_sbts_related_issues_grid_datagrid';

    /**
     * @var IssueSelectType
     */
    private $formType;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->formType = new IssueSelectType();
    }

    protected function assertFormOptions($form)
    {
        $formConfig = $form->getConfig();
        $this->assertEquals(self::AUTOCOMPLETE_ALIAS, $formConfig->getOption('autocomplete_alias'));

        $this->assertEquals(self::GRID_NAME, $formConfig->getOption('grid_name'));
    }

    public function testSetConfigureOptions()
    {
        $resolver = $this->getMock('Symfony\Component\OptionsResolver\OptionsResolver');
        $resolver
            ->expects($this->once())
            ->method('setDefaults')
        ;

        $this->formType->configureOptions($resolver);
    }


    public function testGetName()
    {
        $this->assertEquals(self::NAME, $this->formType->getName());
    }

    public function testGetParent()
    {
        $this->assertEquals(self::PARENT_NAME, $this->formType->getParent());
    }
}
