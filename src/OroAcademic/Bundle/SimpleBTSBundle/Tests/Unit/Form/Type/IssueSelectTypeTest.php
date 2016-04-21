<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Form\Type;

use OroAcademic\Bundle\SimpleBTSBundle\Form\Type\IssueSelectType;

class IssueSelectTypeTest extends \PHPUnit_Framework_TestCase
{
    const NAME = 'oro_academic_sbts_issue_select';
    const PARENT_NAME = 'oro_entity_create_or_select_inline';

    /**
     * @var IssueSelectType
     */
    private $type;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->type = new IssueSelectType();
    }

    public function testSetConfigureOptions()
    {
        $resolver = $this->getMock('Symfony\Component\OptionsResolver\OptionsResolver');
        $resolver
            ->expects($this->once())
            ->method('setDefaults')
        ;

        $this->type->configureOptions($resolver);
    }


    public function testGetName()
    {
        $this->assertEquals(self::NAME, $this->type->getName());
    }

    public function testGetParent()
    {
        $this->assertEquals(self::PARENT_NAME, $this->type->getParent());
    }
}
