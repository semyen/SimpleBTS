<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Controller\Api\Rest;

use OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue;
use OroAcademic\Bundle\SimpleBTSBundle\Form\Type\IssueApiType;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Oro\Bundle\SoapBundle\Form\Handler\ApiFormHandler;
use Oro\Bundle\SoapBundle\Request\Parameters\Filter\HttpDateTimeParameterFilter;
use Oro\Bundle\SoapBundle\Request\Parameters\Filter\IdentifierToReferenceFilter;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Oro\Bundle\AddressBundle\Utils\AddressApiUtils;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;

/**
 * @RouteResource("api")
 * @NamePrefix("oro_academic_sbts_issue_")
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class IssueController extends RestController implements ClassResourceInterface
{
   /**
    * REST GET list
    *
    * @QueryParam(
    *     name="page", requirements="\d+", nullable=true, description="Page number, starting from 1. Defaults to 1."
    * )
    * @QueryParam(
    *     name="limit", requirements="\d+", nullable=true, description="Number of items per page. defaults to 10."
    * )
    * @QueryParam(
    *     name="createdAt",
    *     requirements="\d{4}(-\d{2}(-\d{2}([T ]\d{2}:\d{2}(:\d{2}(\.\d+)?)?(Z|([-+]\d{2}(:?\d{2})?))?)?)?)?",
    *     nullable=true,
    *     description="Date in RFC 3339 format. For example: 2009-11-05T13:15:30Z, 2008-07-01T22:35:17+08:00"
    * )
    * @QueryParam(
    *     name="updatedAt",
    *     requirements="\d{4}(-\d{2}(-\d{2}([T ]\d{2}:\d{2}(:\d{2}(\.\d+)?)?(Z|([-+]\d{2}(:?\d{2})?))?)?)?)?",
    *     nullable=true,
    *     description="Date in RFC 3339 format. For example: 2009-11-05T13:15:30Z, 2008-07-01T22:35:17+08:00"
    * )
    * @QueryParam(
    *     name="ownerId",
    *     requirements="\d+",
    *     nullable=true,
    *     description="Id of owner user"
    * )
    * @QueryParam(
    *     name="ownerUsername",
    *     requirements=".+",
    *     nullable=true,
    *     description="Username of owner user"
    * )
    * @QueryParam(
    *     name="assigneeId",
    *     requirements="\d+",
    *     nullable=true,
    *     description="Id of assignee"
    * )
    * @QueryParam(
    *     name="assigneeUsername",
    *     requirements=".+",
    *     nullable=true,
    *     description="Username of assignee"
    * )
    * @ApiDoc(
    *      description="Get all issue items",
    *      resource=true
    * )
    * @AclAncestor("oro_academic_sbts_issue_view")
    *
    * @throws \Exception
    * @return Response
    */
    public function cgetAction()
    {
        $page  = (int)$this->getRequest()->get('page', 1);
        $limit = (int)$this->getRequest()->get('limit', self::ITEMS_PER_PAGE);

        $dateParamFilter  = new HttpDateTimeParameterFilter();
        $userIdFilter     = new IdentifierToReferenceFilter($this->getDoctrine(), 'OroUserBundle:User');
        $userNameFilter   = new IdentifierToReferenceFilter($this->getDoctrine(), 'OroUserBundle:User', 'username');

        $filterParameters = [
            'createdAt'        => $dateParamFilter,
            'updatedAt'        => $dateParamFilter,
            'ownerId'          => $userIdFilter,
            'ownerUsername'    => $userNameFilter,
            'assigneeId'       => $userIdFilter,
            'assigneeUsername' => $userNameFilter,
        ];

        $map              = [
            'ownerId'          => 'owner',
            'ownerUsername'    => 'owner',
            'assigneeId'       => 'assignee',
            'assigneeUsername' => 'assignee',
        ];

        $criteria = $this->getFilterCriteria($this->getSupportedQueryParameters('cgetAction'), $filterParameters, $map);

        return $this->handleGetListRequest($page, $limit, $criteria);
    }

    /**
     * REST GET item
     *
     * @param string $id
     *
     * @ApiDoc(
     *      description="Get issue item",
     *      resource=true
     * )
     * @AclAncestor("oro_academic_sbts_issue_view")
     * @return Response
     */
    public function getAction($id)
    {
        return $this->handleGetRequest($id);
    }

    /**
     * REST PUT
     *
     * @param int $id Issue item id
     *
     * @ApiDoc(
     *      description="Update issue",
     *      resource=true
     * )
     * @AclAncestor("oro_academic_sbts_issue_update")
     * @return Response
     */
    public function putAction($id)
    {
        return $this->handleUpdateRequest($id);
    }

    /**
     * Create new issue
     *
     * @ApiDoc(
     *      description="Create new issue",
     *      resource=true
     * )
     * @AclAncestor("oro_academic_sbts_issue_create")
     */
    public function postAction()
    {
        return $this->handleCreateRequest();
    }

    /**
     * REST DELETE
     *
     * @param int $id
     *
     * @ApiDoc(
     *      description="Delete issue",
     *      resource=true
     * )
     * @AclAncestor("oro_academic_sbts_issue_delete")
     * @return Response
     */
    public function deleteAction($id)
    {
        return $this->handleDeleteRequest($id);
    }

    /**
     * Get entity Manager
     *
     * @return ApiEntityManager
     */
    public function getManager()
    {
        return $this->get('oro_academic_sbts.issue.manager.api');
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->get('oro_academic_sbts.form.issue.api');
    }

    /**
     * @return ApiFormHandler
     */
    public function getFormHandler()
    {
        return $this->get('oro_academic_sbts.form.handler.issue.api');
    }

    /**
     * @return string
     */
    protected function getFormAlias()
    {
        return IssueApiType::NAME;
    }

    /**
     * {@inheritDoc}
     */
    protected function fixFormData(array &$data, $entity)
    {
        /** @var Issue $entity */
        parent::fixFormData($data, $entity);

        unset($data['id']);
        unset($data['updatedAt']);
        unset($data['createdAt']);

        return true;
    }
}
