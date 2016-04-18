<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Form\Handler;

use Doctrine\ORM\EntityManager;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class LinkIssueHandler
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
     * @param FormInterface $form
     * @param Request       $request
     * @param EntityManager $entityManager
     */
    public function __construct(
        FormInterface $form,
        Request $request,
        EntityManager $entityManager
    ) {
        $this->form = $form;
        $this->request = $request;
        $this->entityManager = $entityManager;
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param Issue $entity
     * @return boolean
     */
    public function process(Issue $entity)
    {
        if ($this->request->isMethod('POST')) {
            $this->getForm()->submit($this->request);

            if ($this->getForm()->isValid()) {
                $relatedIssue = $this->getForm()->get('relatedIssue')->getData();

                if ($relatedIssue->getId() != $entity->getId()) {
                    $entity->addRelatedIssue($relatedIssue);
                    $relatedIssue->addRelatedIssue($entity);

                    $this->entityManager->flush();
                }

                return true;
            }
        }

        return false;
    }
}
