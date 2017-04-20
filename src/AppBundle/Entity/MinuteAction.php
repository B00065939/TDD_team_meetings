<?php
/**
 * Created by PhpStorm.
 * User: bemben
 * Date: 19/04/2017
 * Time: 15:27
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class MinuteAction
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="minute_action")
 */
class MinuteAction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var integer $id
     */
    private $id;

    /**
     * @var string $title
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string $description
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var \DateTime $deadline
     * @ORM\Column(type="datetime")
     */
    private $deadline;

    /**
     * @var MinuteItem $minuteItem
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MinuteItem", inversedBy="actions")
     * @ORM\JoinColumn(name="minute_item_id", referencedColumnName="id")
     */
    private $minuteItem;

    /**
     * @var User $proposer
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="proposedActions")
     * @ORM\JoinColumn(name="proposer_id", referencedColumnName="id")
     */
    private $proposer;

    /**
     * @var User $doer
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="actionsToDo")
     * @ORM\JoinColumn(name="doer_id", referencedColumnName="id")
     */
    private $doer;

    /**
     * @var ActionStatus $status
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ActionStatus", inversedBy="minuteActions")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;

    /********************** GETTERS SETTERS ****************************************/
    /********************** GETTERS SETTERS ****************************************/
    /********************** GETTERS SETTERS ****************************************/

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return \DateTime
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * @param \DateTime $deadline
     */
    public function setDeadline(\DateTime $deadline)
    {
        $this->deadline = $deadline;
    }

    /**
     * @return MinuteItem
     */
    public function getMinuteItem()
    {
        return $this->minuteItem;
    }

    /**
     * @param MinuteItem $minuteItem
     */
    public function setMinuteItem(MinuteItem $minuteItem)
    {
        $this->minuteItem = $minuteItem;
    }

    /**
     * @return User
     */
    public function getProposer()
    {
        return $this->proposer;
    }

    /**
     * @param User $proposer
     */
    public function setProposer(User $proposer)
    {
        $this->proposer = $proposer;
    }

    /**
     * @return User
     */
    public function getDoer()
    {
        return $this->doer;
    }

    /**
     * @param User $doer
     */
    public function setDoer(User $doer)
    {
        $this->doer = $doer;
    }

    /**
     * @return ActionStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param ActionStatus $status
     */
    public function setStatus(ActionStatus $status)
    {
        $this->status = $status;
    }
}