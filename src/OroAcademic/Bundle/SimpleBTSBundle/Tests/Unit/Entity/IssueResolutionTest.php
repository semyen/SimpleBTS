<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Entity;

use OroAcademic\Bundle\SimpleBTSBundle\Entity\IssueResolution;
use Symfony\Component\PropertyAccess\PropertyAccess;

class IssueResolutionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string|null $name
     * @return IssueResolution
     */
    public function getIssueResolution($name = null)
    {
        return new IssueResolution($name);
    }

    /**
     * @dataProvider propertiesDataProvider
     * @param string $property
     * @param mixed  $value
     */
    public function testSettersAndGetters($property, $value)
    {
        $obj = $this->getIssueResolution();

        $accessor = PropertyAccess::createPropertyAccessor();
        $accessor->setValue($obj, $property, $value);
        $this->assertEquals($value, $accessor->getValue($obj, $property));
    }

    /**
     * @return array
     */
    public function propertiesDataProvider()
    {
        return [
            ['label', 'Test Label'],
            ['locale', 'en'],
        ];
    }

    public function testConstructor()
    {
        $testName = 'Test Name';

        $issueResolution = $this->getIssueResolution($testName)->setLabel($testName);
        $this->assertEquals($testName, $issueResolution->getName());
    }

    public function testToString()
    {
        $testName = 'Test Name';

        $issueResolution = $this->getIssueResolution($testName)->setLabel($testName);
        $this->assertEquals($testName, $issueResolution);
    }
}
