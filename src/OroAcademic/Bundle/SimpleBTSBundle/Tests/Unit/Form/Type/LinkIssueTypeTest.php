<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Form\Type;

use OroAcademic\Bundle\SimpleBTSBundle\Form\Type\LinkIssueType;
use Symfony\Component\Validator\Constraints as Assert;

class LinkIssueTypeTest extends \PHPUnit_Framework_TestCase
{
    const NAME = 'oro_academic_sbts_link_issue';

    /**
     * @var LinkIssueType
     */
    private $type;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->type = new LinkIssueType();
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
            ->expects($this->exactly(1))
            ->method('add')
            ->will($this->returnSelf());
        ;

        $this->type->buildForm($builder, []);
    }

    public function testGetName()
    {
        $this->assertEquals(self::NAME, $this->type->getName());
    }
}
