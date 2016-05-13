<?php

namespace OroAcademic\Bundle\SimpleBTSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\TagBundle\Entity\Taggable;
use OroAcademic\Bundle\SimpleBTSBundle\Model\ExtendIssue;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\OrganizationBundle\Entity\Organization;

/**
 * Issue
 *
 * @ORM\Table(name="oro_issue")
 * @ORM\Entity(repositoryClass="OroAcademic\Bundle\SimpleBTSBundle\Entity\Repository\IssueRepository")
 * @ORM\HasLifecycleCallbacks
 * @Config(
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-list-alt"
 *          },
 *          "ownership"={
 *              "owner_type"="USER",
 *              "owner_field_name"="owner",
 *              "owner_column_name"="owner_id",
 *              "organization_field_name"="organization",
 *              "organization_column_name"="organization_id"
 *          },
 *          "tag"={
 *              "enabled"=true
 *          },
 *          "workflow"={
 *              "active_workflow"="simple_bts_issue_workflow"
 *          },
 *          "security"={
 *              "type"="ACL"
 *          },
 *          "dataaudit"={
 *              "auditable"=true
 *          }
 *      }
 * )
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 */
class Issue extends ExtendIssue implements Taggable
{
    const BUG = 'bug';
    const STORY = 'story';
    const SUB_TASK = 'sub_task';
    const TASK = 'task';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=50, nullable=false, unique=true)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "header"="Code",
     *              "order"=10,
     *              "identity"=true
     *          }
     *      }
     * )
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="string", length=255)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "header"="Summary",
     *              "order"=20
     *          }
     *      }
     * )
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "header"="Description",
     *              "order"=30
     *          }
     *      }
     * )
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="text")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "header"="Type",
     *              "order"=40
     *          }
     *      }
     * )
     */
    private $type;

    /**
     * @var IssuePriority
     *
     * @ORM\ManyToOne(targetEntity="IssuePriority")
     * @ORM\JoinColumn(name="issue_priority_id", referencedColumnName="name", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "header"="Priority",
     *              "order"=50
     *          }
     *      }
     * )
     */
    private $priority;

    /**
     * @var IssueResolution
     *
     * @ORM\ManyToOne(targetEntity="IssueResolution")
     * @ORM\JoinColumn(name="issue_resolution_id", referencedColumnName="name", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "header"="Resolution",
     *              "order"=60
     *          }
     *      }
     * )
     */
    private $resolution;

    /**
     * @var ArrayCollection
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $tags;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="reporter_id", referencedColumnName="id")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "header"="Reporter",
     *              "order"=70
     *          }
     *      }
     * )
     */
    private $reporter;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="assignee_id", referencedColumnName="id")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "header"="Assignee",
     *              "order"=80
     *          }
     *      }
     * )
     */
    private $assignee;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Issue")
     * @ORM\JoinTable(name="oro_issue_relation",
     *      joinColumns={@ORM\JoinColumn(name="related_issue_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="linked_issue_id", referencedColumnName="id")}
     * )
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    private $relatedIssues;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinTable(name="oro_issue_collaborators",
     *      joinColumns={@ORM\JoinColumn(name="issue_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    private $collaborators;

    /**
     * @var Issue
     *
     * @ORM\ManyToOne(targetEntity="Issue", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "header"="Parent",
     *              "order"=90
     *          }
     *      }
     * )
     */
    private $parent;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="parent", cascade={"all"})
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    private $children;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "header"="Created At",
     *              "order"=120
     *          }
     *      }
     * )
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "header"="Created At",
     *              "order"=130
     *          }
     *      }
     * )
     */
    private $updatedAt;

    /**
     * @var WorkflowItem
     *
     * @ORM\OneToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowItem")
     * @ORM\JoinColumn(name="workflow_item_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $workflowItem;

    /**
     * @var WorkflowStep
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowStep")
     * @ORM\JoinColumn(name="workflow_step_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $workflowStep;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "header"="Owner",
     *              "order"=100
     *          }
     *      }
     * )
     */
    protected $owner;

    /**
     * @var Organization
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "header"="Organization",
     *              "order"=110
     *          }
     *      }
     * )
     */
    protected $organization;


    /**
     * {@inheritdoc}
     */
    public function getTaggableId()
    {
        return $this->getId();
    }

    /**
     * Issue constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->tags = new ArrayCollection();
        $this->relatedIssues = new ArrayCollection();
        $this->collaborators = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    /**
     * @param WorkflowItem $workflowItem
     * @return Issue
     */
    public function setWorkflowItem($workflowItem)
    {
        $this->workflowItem = $workflowItem;

        return $this;
    }

    /**
     * @return WorkflowItem
     */
    public function getWorkflowItem()
    {
        return $this->workflowItem;
    }

    /**
     * @param WorkflowStep $workflowStep
     * @return Issue
     */
    public function setWorkflowStep($workflowStep)
    {
        $this->workflowStep = $workflowStep;

        return $this;
    }

    /**
     * @return WorkflowStep
     */
    public function getWorkflowStep()
    {
        return $this->workflowStep;
    }

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
     * Set id
     *
     * @return Issue
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set summary
     *
     * @param string $summary
     *
     * @return Issue
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Issue
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        if (empty($this->code)) {
            $this->code = uniqid();
        }

        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Issue
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Issue
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set priority
     *
     * @param IssuePriority $priority
     * @return Issue
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return IssuePriority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set resolution
     *
     * @param string $resolution
     *
     * @return Issue
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * Get resolution
     *
     * @return string
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * Set tags
     *
     * @param string $tags
     *
     * @return Issue
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return string
     */
    public function getTags()
    {
        if (empty($this->tags)) {
            $this->tags = new ArrayCollection();
        }

        return $this->tags;
    }

    /**
     * Set reporter
     *
     * @param string $reporter
     *
     * @return Issue
     */
    public function setReporter($reporter)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return string
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * Set assignee
     *
     * @param string $assignee
     *
     * @return Issue
     */
    public function setAssignee($assignee)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * Get assignee
     *
     * @return string
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * Set relatedIssues
     *
     * @param string $relatedIssues
     *
     * @return Issue
     */
    public function setRelatedIssues($relatedIssues)
    {
        $this->relatedIssues = $relatedIssues;

        return $this;
    }

    /**
     * Get relatedIssues
     *
     * @return string
     */
    public function getRelatedIssues()
    {
        return $this->relatedIssues;
    }

    /**
     * Add related issue
     *
     * @param Issue $issue
     * @return Issue
     */
    public function addRelatedIssue(Issue $issue)
    {
        if (!$this->relatedIssues->contains($issue)) {
            $this->relatedIssues->add($issue);
        }

        return $this;
    }

    /**
     * Remove related issue
     *
     * @param Issue $issue
     * @return Issue
     */
    public function removeRelatedIssue(Issue $issue)
    {
        if ($this->relatedIssues->contains($issue)) {
            $this->relatedIssues->removeElement($issue);
        }

        return $this;
    }

    /**
     * Set collaborators
     *
     * @param ArrayCollection $collaborators
     *
     * @return Issue
     */
    public function setCollaborators($collaborators)
    {
        $this->collaborators = $collaborators;

        return $this;
    }

    /**
     * Add collaborator
     *
     * @param User $collaborator
     *
     * @return Issue
     */
    public function addCollaborator(User $collaborator)
    {
        if (!empty($collaborator->getId()) && !$this->collaborators->contains($collaborator)) {
            $this->collaborators->add($collaborator);
        }

        return $this;
    }

    /**
     * Remove collaborator
     *
     * @param User $collaborator
     *
     * @return Issue
     */
    public function removeCollaborator(User $collaborator)
    {
        $this->collaborators->removeElement($collaborator);

        return $this;
    }

    /**
     * Get collaborators
     *
     * @return ArrayCollection
     */
    public function getCollaborators()
    {
        return $this->collaborators;
    }

    /**
     * Set parent
     *
     * @param string $parent
     *
     * @return Issue
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set children
     *
     * @param string $children
     *
     * @return Issue
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Get children
     *
     * @return string
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Issue
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Issue
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Get owner
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set owner
     *
     * @param User $owner
     * @return Issue
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Set organization
     *
     * @param Organization $organization
     * @return Issue
     */
    public function setOrganization(Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @return bool
     */
    public function isStory()
    {
        if (!empty($this->getType()) && ($this->getType() == Issue::STORY)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isSubTask()
    {
        if (!empty($this->getType()) && ($this->getType() == Issue::SUB_TASK)) {
            return true;
        }

        return false;
    }
}
