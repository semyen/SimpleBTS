<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Form\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue;

use Oro\Bundle\EntityBundle\Tools\EntityRoutingHelper;
use Oro\Bundle\FormBundle\Utils\FormUtils;
use Oro\Bundle\TagBundle\Entity\TagManager;
use Oro\Bundle\TagBundle\Form\Handler\TagHandlerInterface;

/**
 * Class IssueHandler
 * @package OroAcademic\Bundle\SimpleBTSBundle\Form\Handler
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class IssueHandler implements TagHandlerInterface
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var TagManager
     */
    protected $tagManager;

    /**
     * @param FormInterface       $form
     * @param Request             $request
     * @param EntityManager     $entityManager
     */
    public function __construct(
        FormInterface $form,
        Request $request,
        EntityManager $entityManager
    ) {
        $this->form                = $form;
        $this->request             = $request;
        $this->entityManager     = $entityManager;
    }

    /**
     * @param Issue $entity
     * @return boolean True on successful processing, false otherwise
     */
    public function process(Issue $entity)
    {
        if (empty($entity->getCreatedAt())) {
            $currentDate = new \DateTime('now', new \DateTimeZone('UTC'));
            $entity->setCreatedAt($currentDate)->setUpdatedAt($currentDate);
        }

        $this->form->setData($entity);

        if (in_array($this->request->getMethod(), ['POST', 'PUT'])) {
            $this->form->submit($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($entity);

                return true;
            }
        }

        return false;
    }

    /**
     * @param Issue $entity
     */
    protected function onSuccess(Issue $entity)
    {
        $this->entityManager->getRepository('OroAcademicSimpleBTSBundle:Issue')->update($entity);

        if ($this->tagManager) {
            $this->tagManager->saveTagging($entity);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setTagManager(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
    }
}
