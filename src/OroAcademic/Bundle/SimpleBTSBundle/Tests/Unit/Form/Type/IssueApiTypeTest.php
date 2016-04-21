<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Form\Type;

use OroAcademic\Bundle\SimpleBTSBundle\Form\Type\IssueApiType;

class IssueApiTypeTest extends \PHPUnit_Framework_TestCase
{
    const NAME = 'oro_academic_sbts_issue_api';
    const PARENT_NAME = 'oro_academic_sbts_issue';

    /**
     * @var IssueApiType
     */
    protected $type;

    protected function setUp()
    {
        $this->type = new IssueApiType();
    }

    protected function tearDown()
    {
        unset($this->type);
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

    public function testBuildForm()
    {
        $builder =
            $this
                ->getMockBuilder('Symfony\Component\Form\FormBuilder')
                ->disableOriginalConstructor()
                ->getMock()
        ;

        $builder
            ->expects($this->exactly(3))
            ->method('add')
            ->will($this->returnSelf());
        ;

        $this->type->buildForm($builder, []);
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
