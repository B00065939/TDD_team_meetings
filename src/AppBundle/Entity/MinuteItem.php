<?php
/**
 * Created by PhpStorm.
 * User: bemben
 * Date: 19/04/2017
 * Time: 15:11
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class MinuteItem
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="minute_item")
 */
class MinuteItem
{

    function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->actions = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var integer $id
     */
    private $id;

    /**
     * @var string $title
     * @ORM\Column(name="title")
     */
    private $title;

    /**
     * @var string $description
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var Meeting $meeting
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Meeting", inversedBy="minuteItems")
     * @ORM\JoinColumn(name="meeting_id", referencedColumnName="id")
     */
    private $meeting;
    /**
     * @var AgendaStatus $status
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\AgendaStatus", inversedBy="minuteItems")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;

    /**
     * @var User $proposer
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="minuteItems")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $proposer;

    /**
     * @var int $sequenceNo
     * @ORM\Column(name="sequence_no", type="integer")
     */
    private $sequenceNo;

    /**
     * @var \DateTime $creationDate
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private $creationDate;

    /**
     * @var MinuteAction $actions
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MinuteAction", mappedBy="minuteItem")
     */
    private $actions;

    /**
     * @var string $comment
     * @ORM\Column(type="text")
     */
    private $comment;
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
     * @return Meeting
     */
    public function getMeeting()
    {
        return $this->meeting;
    }

    /**
     * @param Meeting $meeting
     */
    public function setMeeting($meeting)
    {
        $this->meeting = $meeting;
    }

    /**
     * @return AgendaStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param AgendaStatus $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
    public function setProposer($proposer)
    {
        $this->proposer = $proposer;
    }

    /**
     * @return int
     */
    public function getSequenceNo()
    {
        return $this->sequenceNo;
    }

    /**
     * @param int $sequenceNo
     */
    public function setSequenceNo($sequenceNo)
    {
        $this->sequenceNo = $sequenceNo;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param \DateTime $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return MinuteAction
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param MinuteAction $action
     */
    public function addActions($action)
    {
        $this->actions[] = $action;
    }

    /**
     * @param MinuteAction $action
     */
    public function removeAction(MinuteAction $action)
    {
        $this->actions->removeElement($action);
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }


}