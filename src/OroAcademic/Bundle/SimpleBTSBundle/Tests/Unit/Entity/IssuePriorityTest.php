<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Entity;

use OroAcademic\Bundle\SimpleBTSBundle\Entity\IssuePriority;
use Symfony\Component\PropertyAccess\PropertyAccess;

class IssuePriorityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string|null $name
     * @return IssuePriority
     */
    public function getIssuePriority($name = null)
    {
        return new IssuePriority($name);
    }

    /**
     * @dataProvider propertiesDataProvider
     * @param string $property
     * @param mixed  $value
     */
    public function testSettersAndGetters($property, $value)
    {
        $obj = $this->getIssuePriority();

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
            ['priorityOrder', 1],
        ];
    }

    public function testConstructor()
    {
        $testName = 'Test Name';

        $issuePriority = $this->getIssuePriority($testName)->setLabel($testName);
        $this->assertEquals($testName, $issuePriority->getName());
    }

    public function testToString()
    {
        $testName = 'Test Name';

        $issuePriority = $this->getIssuePriority($testName)->setLabel($testName);
        $this->assertEquals($testName, $issuePriority);
    }
}
