<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Form\Type;

use OroAcademic\Bundle\SimpleBTSBundle\Form\Type\LinkIssueType;
use Symfony\Component\Validator\Constraints as Assert;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue;
use OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Form\Extension\Stub\FormTypeValidatorExtensionStub;
use OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Form\Type\Stub\EntityType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\FormIntegrationTestCase;

class LinkIssueTypeTest extends FormIntegrationTestCase
{
    const NAME = 'oro_academic_sbts_link_issue';

    /**
     * @var LinkIssueType
     */
    private $formType;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->formType = new LinkIssueType();
    }

    protected function getExtensions()
    {
        return array_merge(
            parent::getExtensions(),
            [
                new PreloadedExtension(
                    [
                        'oro_academic_sbts_issue_select' => new EntityType(
                            [1 => [$this->getEntity(Issue::class, 'id', '1')]],
                            'oro_academic_sbts_issue_select'
                        ),
                    ],
                    ['form' => [new FormTypeValidatorExtensionStub()]]
                ),
            ]
        );
    }
    
    /**
     * @param string $className
     * @param string $idField
     * @param string $idValue
     * @return object
     */
    protected function getEntity($className, $idField, $idValue)
    {
        $entity = new $className;

        $reflectionClass = new \ReflectionClass($className);
        $method = $reflectionClass->getProperty($idField);
        $method->setAccessible(true);
        $method->setValue($entity, $idValue);

        return $entity;
    }

    /**
     * @dataProvider getValidTestData
     * @param $data
     */
    public function testSubmitValidData($data)
    {
        $form = $this->factory->create($this->formType);

        $form->submit($data);


        $this->assertTrue($form->isValid());
        $this->assertTrue($form->isSynchronized());

        $formData = $form->getData();

        $this->assertNotEmpty($formData['relatedIssue']);
        $this->assertNotEmpty($formData['relatedIssue'][0]);

        $relatedIssue = $formData['relatedIssue'][0];

        $this->assertTrue($relatedIssue instanceof Issue);
        $this->assertEquals($relatedIssue->getId(), 1);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($data) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    /**
     * @return array
     */
    public function getValidTestData()
    {
        return [
            [
                'data' => [
                    'relatedIssue' => 1
                ]
            ]
        ];
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

        $this->formType->buildForm($builder, []);
    }

    public function testGetName()
    {
        $this->assertEquals(self::NAME, $this->formType->getName());
    }
}
