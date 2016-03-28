<?php

namespace Oro\Bundle\SimpleBTSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

/**
 * IssuePriority
 *
 * @ORM\Table(name="oro_sbts_issue_priority")
 * @Config()
 * @ORM\Entity
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class IssuePriority
{
    const PRIORITY_BLOCKER  = 'blocker';
    const PRIORITY_CRITICAL = 'critical';
    const PRIORITY_MAJOR    = 'major';
    const PRIORITY_MINOR    = 'minor';
    const PRIORITY_TRIVIAL  = 'trivial';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return IssuePriority
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

