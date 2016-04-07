<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class IssueType extends AbstractType
{
    const BUG = 'bug';
    const SUB_TASK = 'sub_task';
    const TASK = 'task';
    const STORY = 'story';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'code',
                'hidden',
                [
                    'required'    => true,
                ]
            )
            ->add(
                'summary',
                'text',
                [
                    'required'    => true,
                    'label'       => 'oroacademic.simplebts.issue.summary.label',
                    'constraints' => [
                        new Assert\NotBlank()
                    ]
                ]
            )
            ->add(
                'description',
                'textarea',
                [
                    'required' => false,
                    'label'    => 'oroacademic.simplebts.issue.description.label'
                ]
            )
            ->add('type', 'choice', [
                'label'       => 'oroacademic.simplebts.issue.type.label',
                'choices' => [
                    self::BUG => 'oroacademic.simplebts.issue.form.issue_type.bug.label',
                    self::SUB_TASK => 'oroacademic.simplebts.issue.form.issue_type.sub_task.label',
                    self::TASK => 'oroacademic.simplebts.issue.form.issue_type.task.label',
                    self::STORY => 'oroacademic.simplebts.issue.form.issue_type.story.label',
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add(
                'priority',
                'translatable_entity',
                [
                    'label'    => 'oroacademic.simplebts.issue.priority.label',
                    'required' => true,
                    'property' => 'label',
                    'class' => 'OroAcademic\Bundle\SimpleBTSBundle\Entity\IssuePriority',
                    'constraints' => [
                        new Assert\NotBlank()
                    ]
                ]
            )
            ->add(
                'assignee',
                'oro_user_select',
                [
                    'label' => 'oroacademic.simplebts.issue.assignee.label',
                    'required' => false,
                ]
            )
            ->add(
                'tags',
                'oro_tag_select',
                [
                    'label' => 'oro.tag.entity_plural_label',
                ]
            );
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue',
            'cascade_validation' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'oro_academic_sbts_issue';
    }
}
