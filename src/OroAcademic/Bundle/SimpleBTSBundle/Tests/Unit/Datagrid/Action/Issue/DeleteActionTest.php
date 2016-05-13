<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Datagrid\Action\Issue;

use OroAcademic\Bundle\SimpleBTSBundle\Datagrid\Action\Issue\DeleteAction;

class DeleteActionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetOptions()
    {
        $action = new DeleteAction();
        $options = $action->getOptions();

        $this->assertInstanceOf('Oro\Bundle\DataGridBundle\Extension\Action\Actions\AjaxAction', $action);
        $this->assertInstanceOf('Oro\Bundle\DataGridBundle\Extension\Action\ActionConfiguration', $options);
        $this->assertCount(2, $options);
        $this->assertArrayHasKey('frontend_type', $options);
        $this->assertEquals('issue-delete', $options['frontend_type']);
    }
}
