<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => true,
            'configs'            => [
                'placeholder' => 'oroacademic.simplebts.issue.form.select_issue.label',
            ],
            'grid_name' => 'oro_academic_sbts_related_issues_grid_datagrid',
            'autocomplete_alias' => 'issues',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'oro_entity_create_or_select_inline';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'oro_academic_sbts_issue_select';
    }
}
