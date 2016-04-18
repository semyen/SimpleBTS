<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Controller;

use OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue;
use OroAcademic\Bundle\SimpleBTSBundle\Form\Type\IssueType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class IssueController extends Controller
{
    /**
     * @Route("/", name="oro_academic_sbts_issue_index")
     * @Template()
     * @return RedirectResponse
     * @AclAncestor("oro_academic_sbts_issue_view")
     */
    public function indexAction()
    {
        return [
            'entity_class' => Issue::class
        ];
    }

    /**
     * @Route("/active/full/view", name="oro_academic_sbts_issue_active_full_view")
     * @Template()
     * @return RedirectResponse
     * @AclAncestor("oro_academic_sbts_issue_view")
     */
    public function activeFullViewAction()
    {
        return [
            'entity_class' => Issue::class,
        ];
    }

    /**
     * @Route("/user/issues/{userId}", name="oro_academic_sbts_issue_user_issues", requirements={"userId"="\d+"})
     * @Template()
     * @param int $userId
     * @return array
     * @AclAncestor("oro_academic_sbts_issue_view")
     */
    public function userIssuesAction($userId)
    {
        return [
            'userId' => $userId,
        ];
    }

    /**
     * @Route("/view/{id}", name="oro_academic_sbts_issue_view", requirements={"id"="\d+"})
     * @Template()
     * @param Issue $entity
     * @return array
     * @Acl(
     *      id="oro_academic_sbts_issue_view",
     *      type="entity",
     *      class="OroAcademicSimpleBTSBundle:Issue",
     *      permission="VIEW"
     * )
     */
    public function viewAction(Issue $entity)
    {
        return [
            'entity' => $entity,
        ];
    }

    /**
     * @Route("/create", name="oro_academic_sbts_issue_create")
     * @Template("OroAcademicSimpleBTSBundle:Issue:update.html.twig")
     * @param Request $request
     * @return RedirectResponse
     * @Acl(
     *      id="oro_academic_sbts_issue_create",
     *      type="entity",
     *      class="OroAcademicSimpleBTSBundle:Issue",
     *      permission="CREATE"
     * )
     */
    public function createAction(Request $request)
    {
        $issue = new Issue();

        $parentId = (int)$request->query->get('parentId', 0);

        if (!empty($parentId)) {
            $parentIssue = $this->getDoctrine()->getRepository('OroAcademicSimpleBTSBundle:Issue')->find($parentId);

            if (!empty($parentIssue) && $parentIssue->isStory()) {
                $issue->setParent($parentIssue);
                $issue->setType(IssueType::SUB_TASK);
            } else {
                if (empty($parentIssue)) {
                    $this->get('session')->getFlashBag()->add(
                        'error',
                        $this->get('translator')->trans('oroacademic.simplebts.issue.form.parent_not_found')
                    );
                } elseif (!$parentIssue->isStory()) {
                    $this->get('session')->getFlashBag()->add(
                        'error',
                        $this->get('translator')->trans('oroacademic.simplebts.issue.form.parent_can_not_have_subtasks')
                    );
                }
            }
        }

        $formAction = $this->get('oro_entity.routing_helper')
            ->generateUrlByRequest(
                'oro_academic_sbts_issue_create',
                $request
            )
        ;

        return $this->update($issue, $formAction, $request);
    }

    /**
     * @Route("/update/{id}", name="oro_academic_sbts_issue_update", requirements={"id":"\d+"})
     * @Template()
     * @param Issue $issue
     * @param Request $request
     * @return RedirectResponse
     * @AclAncestor("oro_academic_sbts_issue_create")
     */
    public function updateAction(Issue $issue, Request $request)
    {
        $formAction = $request->getUri();

        return $this->update($issue, $formAction, $request);
    }

    /**
     * @Route("/delete/{id}", name="oro_academic_sbts_issue_delete", requirements={"id":"\d+"})
     * @Template()
     * @param Issue $issue
     * @return RedirectResponse
     * @Acl(
     *      id="oro_academic_sbts_issue_delete",
     *      type="entity",
     *      class="OroAcademicSimpleBTSBundle:Issue",
     *      permission="DELETE"
     * )
     */
    public function deleteAction(Issue $issue)
    {
        $this->getDoctrine()->getEntityManager()->getRepository('OroAcademicSimpleBTSBundle:Issue')->delete($issue);

        return $this->redirect($this->generateUrl('oro_academic_sbts_issue_index'));
    }

    /**
     * @Route("/link/{id}", name="oro_academic_sbts_issue_link", requirements={"id"="\d+"})
     * @Template("OroAcademicSimpleBTSBundle:Issue\widget:link.html.twig")
     * @param Issue $issue
     * @param Request $request
     * @return array
     * @AclAncestor("oro_academic_sbts_issue_create")
     */
    public function linkAction(Issue $issue, Request $request)
    {
        $formAction = $request->getUri();

        return $this->link($issue, $formAction);
    }

    /**
     * @Route(
     *     "/unlink/{id}/from/{linkedId}",
     *     name="oro_academic_sbts_issue_unlink",
     *     requirements={"id"="\d+", "linkedId"="\d+"}
     * )
     * @param Issue $issue
     * @param Issue $relatedIssue
     * @ParamConverter("issue", class="OroAcademicSimpleBTSBundle:Issue", options={"id" = "id"})
     * @ParamConverter("relatedIssue", class="OroAcademicSimpleBTSBundle:Issue", options={"id" = "linkedId"})
     * @return Response
     * @AclAncestor("oro_academic_sbts_issue_create")
     */
    public function unlinkAction(Issue $issue, Issue $relatedIssue)
    {
        $this->unlink($issue, $relatedIssue);

        return new Response();
    }

    /**
     * @param Issue $issue
     * @param string $formAction
     * @param Request $request
     * @return array|RedirectResponse
     */
    private function update(Issue $issue, $formAction, Request $request)
    {
        if ($this->get('oro_academic_sbts.form.handler.issue')->process($issue)) {
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('oroacademic.simplebts.issue.form.saved')
            );

            if (!$request->get('_widgetContainer')) {
                return $this->get('oro_ui.router')->redirectAfterSave(
                    ['route' => 'oro_academic_sbts_issue_update', 'parameters' => ['id' => $issue->getId()]],
                    ['route' => 'oro_academic_sbts_issue_index'],
                    $issue
                );
            } else {
                return array(
                    'issue'  => $issue,
                    'form'    => $this->get('oro_academic_sbts.form.issue')->createView(),
                    'formAction' => $formAction,
                    'result'  => 'success',
                );
            }
        }

        return array(
            'issue'  => $issue,
            'form'    => $this->get('oro_academic_sbts.form.issue')->createView(),
            'formAction' => $formAction,
        );
    }

    /**
     * @param Issue $issue
     * @param string $formAction
     * @return array
     */
    protected function link(Issue $issue, $formAction)
    {
        return [
            'entity'     => $issue,
            'form'       => $this->get('oro_academic_sbts.form.handler.link_issue')->getForm()->createView(),
            'formAction' => $formAction,
            'saved'      => $this->get('oro_academic_sbts.form.handler.link_issue')->process($issue) ? true : false,
        ];
    }

    /**
     * @param Issue $issue
     * @param Issue $relatedIssue
     * @return bool
     */
    protected function unlink(Issue $issue, Issue $relatedIssue)
    {
        $issue->removeRelatedIssue($relatedIssue);
        $relatedIssue->removeRelatedIssue($issue);

        $this->getDoctrine()->getEntityManager()->flush();

        return true;
    }
}
