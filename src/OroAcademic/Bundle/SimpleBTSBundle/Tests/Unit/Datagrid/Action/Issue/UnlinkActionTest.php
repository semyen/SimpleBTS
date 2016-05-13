<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Tests\Unit\Datagrid\Action\Issue;

use OroAcademic\Bundle\SimpleBTSBundle\Datagrid\Action\Issue\UnlinkAction;

class UnlinkActionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetOptions()
    {
        $action = new UnlinkAction();
        $options = $action->getOptions();

        $this->assertInstanceOf('Oro\Bundle\DataGridBundle\Extension\Action\Actions\AjaxAction', $action);
        $this->assertInstanceOf('Oro\Bundle\DataGridBundle\Extension\Action\ActionConfiguration', $options);
        $this->assertCount(2, $options);
        $this->assertArrayHasKey('frontend_type', $options);
        $this->assertEquals('unlink', $options['frontend_type']);
    }
}
