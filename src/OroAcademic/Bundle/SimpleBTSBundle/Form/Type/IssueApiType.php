<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Form\Type;

use Oro\Bundle\SoapBundle\Form\EventListener\PatchSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class IssueApiType extends AbstractType
{
    const NAME = 'oro_academic_sbts_issue_api';
    const PARENT_NAME = 'oro_academic_sbts_issue';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'createdAt',
                'oro_datetime',
                ['required' => false]
            )
            ->add(
                'updatedAt',
                'oro_datetime',
                ['required' => false]
            )
            ->add(
                'reporter',
                'oro_user_select',
                [
                    'label' => 'oroacademic.simplebts.issue.assignee.label',
                    'required' => false,
                ]
            )
        ;

        $builder->addEventSubscriber(new PatchSubscriber());
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return self::PARENT_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }
}
