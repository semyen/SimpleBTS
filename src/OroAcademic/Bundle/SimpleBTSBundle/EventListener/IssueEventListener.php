<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\EventListener;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use OroAcademic\Bundle\SimpleBTSBundle\Entity\Issue;
use Symfony\Component\DependencyInjection\ContainerInterface;

class IssueEventListener
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    
    /**
     * @var ArrayCollection
     */
    protected $issues;

    /**
     * @var bool
     */
    protected $busy = false;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->issues = new ArrayCollection();
    }

    public function onFlush(OnFlushEventArgs $event)
    {
        foreach ($event->getEntityManager()->getUnitOfWork()->getScheduledEntityInsertions() as $entity) {
            if (($entity instanceof Issue) && (empty($entity->getId()))) {
                if (strpos($entity->getCode(), '-') === false) {
                    $this->issues[] = $entity;
                }
            }
        }
    }

    public function postFlush(PostFlushEventArgs $event)
    {
        if (!$this->busy && !empty($this->issues)) {
            foreach ($this->issues as $issue) {
                if (strpos($issue->getCode(), '-') === false) {
                    $issue->setCode($issue->getOrganization()->getName() . '-' . $issue->getId());
                    if (!empty($this->container->get('security.token_storage')->getToken())) {
                        $issue->setReporter($this->container->get('security.token_storage')->getToken()->getUser());
                    }
                    //OroEntityManager doesn't have removeEventListener method.
                    //$event->getEntityManager()->removeEventListener('onFlush', $this);
                    $this->busy = true;
                    $event->getEntityManager()->persist($issue);
                    $event->getEntityManager()->flush($issue);
                    //$event->getEntityManager()->addEventListener('onFlush', $this);
                    $this->busy = false;
                }
            }
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getIssues()
    {
        return $this->issues;
    }
}
