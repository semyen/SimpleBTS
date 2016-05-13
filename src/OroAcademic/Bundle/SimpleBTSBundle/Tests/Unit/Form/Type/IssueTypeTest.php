<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Form\Type;

use Oro\Bundle\UserBundle\Entity\User;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\IssuePriority;
use OroAcademic\Bundle\SimpleBTSBundle\Form\Type\IssueType;
use OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Form\Extension\Stub\FormTypeValidatorExtensionStub;
use OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Form\Type\Stub\EntityType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\FormIntegrationTestCase;

class IssueTypeTest extends FormIntegrationTestCase
{
    const FORM_NAME = 'oro_academic_sbts_issue';
    const ISSUE_CODE = 'ORO-1';
    const BUILD_ADD_COUNT = 7;

    /**
     * @var IssueType
     */
    private $formType;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->formType = new IssueType();
    }

    protected function getExtensions()
    {
        return array_merge(
            parent::getExtensions(),
            [
                new PreloadedExtension(
                    [
                        'translatable_entity' => new EntityType(
                            [1 => $this->getEntity(IssuePriority::class, 'name', 'major')],
                            'translatable_entity'
                        ),
                        'oro_user_select' => new EntityType(
                            [1 => $this->getEntity(User::class, 'id', '1')],
                            'oro_user_select'
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
        $this->assertFormOptions($form);

        $form->submit($data);

        $this->assertTrue($form->isValid());
        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($form->getData()->getCode(), self::ISSUE_CODE);

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
                    'code' => self::ISSUE_CODE,
                    'summary' => 'Test Summary',
                    'description' => 'Test Description',
                    'type' => Issue::TASK,
                    'priority' => IssuePriority::MINOR,
                    'assignee' => 2,
                    'owner' => 1
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
            ->expects($this->exactly(self::BUILD_ADD_COUNT))
            ->method('add')
            ->will($this->returnSelf());
        ;

        $this->formType->buildForm($builder, []);
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

    protected function assertFormOptions($form)
    {
        $formConfig = $form->getConfig();
        $this->assertEquals(Issue::class, $formConfig->getOption('data_class'));
    }

    public function testGetName()
    {
        $this->assertEquals(self::FORM_NAME, $this->formType->getName());
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
