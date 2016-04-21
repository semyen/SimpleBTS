<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Form\Type;

use OroAcademic\Bundle\SimpleBTSBundle\Form\Type\IssueType;

class IssueTypeTest extends \PHPUnit_Framework_TestCase
{
    const NAME = 'oro_academic_sbts_issue';

    /**
     * @var IssueType
     */
    private $type;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->type = new IssueType();
    }

    public function testBuildForm()
    {
        $builder =
            $this
                ->getMockBuilder('Symfony\Component\Form\FormBuilder')
                ->disableOriginalConstructor()
                ->getMock()
        ;

        $builder
            ->expects($this->exactly(8))
            ->method('add')
            ->will($this->returnSelf());
        ;

        $this->type->buildForm($builder, []);
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
}
