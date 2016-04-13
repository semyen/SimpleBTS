<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * IssuePriority
 *
 * @ORM\Entity
 * @ORM\Table(name="oro_issue_priority")
 * @Gedmo\TranslationEntity(class="OroAcademic\Bundle\SimpleBTSBundle\Entity\IssuePriorityTranslation")
 * @Config(
 *     defaultValues={
 *          "grouping"={
 *              "groups"={"dictionary"}
 *          }
 *      }
 * )
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class IssuePriority
{
    const BLOCKER  = 'blocker';
    const CRITICAL = 'critical';
    const MAJOR    = 'major';
    const MINOR    = 'minor';
    const TRIVIAL  = 'trivial';

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @ORM\Id
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "identity"=true
     *          }
     *      }
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255, unique=true)
     * @Gedmo\Translatable
     */
    protected $label;

    /**
     * @Gedmo\Locale
     */
    protected $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="priority_order", type="integer")
     */
    protected $priorityOrder;

    /**
     * @param string|null $name
     */
    public function __construct($name = null)
    {
        if (!empty($name)) {
            $this->name = $name;
        }
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->__toString();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return IssuePriority
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return IssuePriority
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return IssuePriority
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Returns locale code
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->label;
    }

    /**
     * Set entity priorityOrder
     *
     * @param integer $priorityOrder
     * @return IssuePriority
     */
    public function setPriorityOrder($priorityOrder)
    {
        $this->priorityOrder = $priorityOrder;

        return $this;
    }

    /**
     * Get entity priorityOrder
     *
     * @return integer
     */
    public function getPriorityOrder()
    {
        return $this->priorityOrder;
    }
}
