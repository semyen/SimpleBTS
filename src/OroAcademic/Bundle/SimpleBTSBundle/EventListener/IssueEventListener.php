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
            if (($entity instanceof Issue) && (!empty($entity->getId()))) {
                $this->issues[] = $entity;
            }
        }
    }

    public function postFlush(PostFlushEventArgs $event)
    {
        if (!empty($this->issues)) {
            foreach ($this->issues as $issue) {
                $issue->setCode($issue->getOrganization()->getName() . '-' . $issue->getId());
                $issue->setReporter($this->container->get('security.token_storage')->getToken()->getUser());
                $event->getEntityManager()->persist($issue);
                $event->getEntityManager()->flush($issue);
            }
        }
    }
}
