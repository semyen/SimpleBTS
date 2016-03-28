<?php

namespace Oro\Bundle\SimpleBTSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

/**
 * IssueType
 *
 * @ORM\Table("oro_sbts_issue_type")
 * @Config()
 * @ORM\Entity
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class IssueType
{
    const TYPE_BUG = 'bug';
    const TYPE_SUB_TASK = 'sub_task';
    const TYPE_TASK = 'task';
    const TYPE_STORY = 'story';

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
     * @return IssueType
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

