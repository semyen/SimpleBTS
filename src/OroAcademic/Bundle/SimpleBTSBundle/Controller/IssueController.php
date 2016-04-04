<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Controller;

use OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IssueController extends Controller
{
    /**
     * @Route("/", name="oro_academic_sbts_issue_index")
     * @Template()
     * @return RedirectResponse
     */
    public function indexAction()
    {
        return new Response();
    }

    /**
     * @Route("/create", name="oro_academic_sbts_issue_create")
     * @Template("OroAcademicSimpleBTSBundle:Issue:update.html.twig")
     * @param Request $request
     * @return RedirectResponse
     */
    public function createAction(Request $request)
    {
        $formAction = $this->get('oro_entity.routing_helper')
            ->generateUrlByRequest(
                'oro_academic_sbts_issue_create',
                $request
            )
        ;

        return $this->update(new Issue(), $formAction);
    }

    /**
     * @Route("/update/{id}", name="oro_academic_sbts_issue_update", requirements={"id":"\d+"})
     * @Template()
     * @param Issue $issue
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateAction(Issue $issue, Request $request)
    {
        $formAction = $request->getUri();

        return $this->update($issue, $formAction);
    }

    /**
     * @param Issue $issue
     * @param string $formAction
     * @return array|RedirectResponse
     */
    private function update(Issue $issue, $formAction)
    {
        if ($this->get('oro_academic_sbts.form.handler.issue')->process($issue)) {
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('oro.academic.simplebts.issue.form.issue.saved')
            );

            return $this->get('oro_ui.router')->redirectAfterSave(
                ['route' => 'oro_academic_sbts_issue_update', 'parameters' => ['id' => $issue->getId()]],
                ['route' => 'oro_academic_sbts_issue_index'],
                $issue
            );
        }

        return array(
            'issue'  => $issue,
            'form'    => $this->get('oro_academic_sbts.form.issue')->createView(),
            'formAction' => $formAction,
        );
    }
}