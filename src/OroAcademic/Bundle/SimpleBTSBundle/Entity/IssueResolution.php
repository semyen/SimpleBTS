<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * IssueResolution
 *
 * @ORM\Entity
 * @ORM\Table("oro_issue_resolut")
 * @Gedmo\TranslationEntity(class="OroAcademic\Bundle\SimpleBTSBundle\Entity\IssuePriorityTranslation")
 * @Config(
 *     defaultValues={
 *          "grouping"={
 *              "groups"={"dictionary"}
 *          }
 *      }
 * )
 */
class IssueResolution
{
    const FIXED            = 'fixed';
    const WONT_FIX         = 'wont_fix';
    const DUPLICATE        = 'duplicate';
    const INCOMPLETE       = 'incomplete';
    const CANNOT_REPRODUCE = 'cannot_reproduce';
    const DONE             = 'done';
    const WONT_DO          = 'wont_do';
    const REJECTED          = 'rejected';

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
     * Set address type label
     *
     * @param string $label
     * @return IssueResolution
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get address type label
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
     * @return IssueResolution
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
}
