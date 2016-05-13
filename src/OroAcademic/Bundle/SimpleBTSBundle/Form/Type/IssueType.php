<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class IssueType extends AbstractType
{
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
                    Issue::BUG => 'oroacademic.simplebts.issue.form.issue_type.bug.label',
                    Issue::SUB_TASK => 'oroacademic.simplebts.issue.form.issue_type.sub_task.label',
                    Issue::TASK => 'oroacademic.simplebts.issue.form.issue_type.task.label',
                    Issue::STORY => 'oroacademic.simplebts.issue.form.issue_type.story.label',
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
                'owner',
                'oro_user_select',
                [
                    'required' => true,
                    'label' => 'oroacademic.simplebts.issue.owner.label',
                ]
            )
            /*->add(
                'tags',
                'oro_tag_select',
                [
                    'required' => false,
                    'label' => 'oro.tag.entity_plural_label',
                ]
            )*/;
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
