<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Datagrid\Action\Issue;

use Oro\Bundle\DataGridBundle\Extension\Action\ActionConfiguration;
use Oro\Bundle\DataGridBundle\Extension\Action\Actions\AjaxAction;

class UnlinkAction extends AjaxAction
{
    /**
     * @return ActionConfiguration
     */
    public function getOptions()
    {
        $options = parent::getOptions();
        $options['frontend_type'] = 'unlink';

        return $options;
    }
}
