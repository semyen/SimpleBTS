<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;

class DashboardController extends Controller
{
    /**
     * @Route(
     *      "/issue_status/chart/{widget}",
     *      name="oro_academic_sbts_dashboard_issue_by_status",
     *      requirements={"widget"="[\w-]+"}
     * )
     * @Template("OroAcademicSimpleBTSBundle:Dashboard:issueByStatus.html.twig")
     * @param string $widget
     * @return array
     * @Acl(
     *      id="oro_academic_sbts_dashboard_issue_by_status",
     *      type="entity",
     *      class="OroAcademicSimpleBTSBundle:Issue",
     *      permission="VIEW"
     * )
     */
    public function issueByStatusAction($widget)
    {
        $workflowSteps = $this->getDoctrine()->getRepository('OroWorkflowBundle:WorkflowStep')->findAll();

        $data = $this->getDoctrine()->getRepository('OroAcademicSimpleBTSBundle:Issue')
            ->getIssueByStatus($this->get('oro_security.acl_helper'), $workflowSteps)
        ;

        $widgetAttr = $this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig($widget);
        $widgetAttr['chartView'] = $this->get('oro_chart.view_builder')
            ->setArrayData($data)
            ->setOptions([
                'name' => 'bar_chart',
                'data_schema' => [
                    'label' => ['field_name' => 'label'],
                    'value' => ['field_name' => 'count']
                ],
                'settings' => ['xNoTicks' => count($data)]
            ])
            ->getView();

        return $widgetAttr;
    }
}
