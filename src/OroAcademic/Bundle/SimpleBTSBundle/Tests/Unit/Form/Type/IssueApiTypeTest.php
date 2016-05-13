<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Form\Type;

use OroAcademic\Bundle\SimpleBTSBundle\Form\Type\IssueApiType;

class IssueApiTypeTest extends IssueTypeTest
{
    const FORM_NAME = 'oro_academic_sbts_issue_api';
    const PARENT_NAME = 'oro_academic_sbts_issue';
    const BUILD_ADD_COUNT = 3;

    /**
     * @var IssueApiType
     */
    protected $formType;

    protected function setUp()
    {
        parent::setUp();
        $this->formType = new IssueApiType();
    }

    public function testGetParent()
    {
        $this->assertEquals(self::PARENT_NAME, $this->formType->getParent());
    }
}
