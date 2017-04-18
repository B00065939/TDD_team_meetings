<?php
/**
 * Created by PhpStorm.
 * User: bemben
 * Date: 13/04/2017
 * Time: 15:41
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class AgendaItem
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AgendaItemRepository")
 * @ORM\Table(name="agenda_item")
 */
class AgendaItem
{
    function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->nextVersions = new ArrayCollection();
        $this->prevVersions = new ArrayCollection();
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Meeting", inversedBy="agendaItems")
     * @ORM\JoinColumn(name="meeting_id", referencedColumnName="id")
     */
    private $meeting;
    /**
     * @var AgendaStatus $status
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\AgendaStatus", inversedBy="agendaItems")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;
////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * One agenda item has may lestVersion agenda item
     * @var ArrayCollection $prevVersions
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AgendaItem", mappedBy="replacedBy")
     */
    private $prevVersions;

    /**
     * @var AgendaItem $replacedBy
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\AgendaItem", inversedBy="prevVersions")
     * @ORM\JoinColumn(name="replaced_by_id", referencedColumnName="id")     *
     */
    private $replacedBy;

    //////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * One agenda item has many next versions
     * @var ArrayCollection $nextVersions
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AgendaItem", mappedBy="updateFor")
     */
    private $nextVersions;

    /**
     * @var AgendaItem $updateFor
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\AgendaItem", inversedBy="nextVersions")
     * @ORM\JoinColumn(name="update_for_id", referencedColumnName="id")
     */
    private $updateFor;

    ///////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Meeting", inversedBy="postponedAgendaItems")
     * @ORM\JoinColumn(name="postponed_to", referencedColumnName="id")
     * @var Meeting $postponedTo
     */
    private $postponedTo;

    /**
     * @var int $sequenceNo
     * @ORM\Column(name="sequence_no", type="integer")
     */
    private $sequenceNo;

    /**
     * @var User $proposer
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="agendaItems")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     */
    private $proposer;

    /**
     * @var \DateTime $creationDate
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private $creationDate;

    /********************** GETTERS SETTERS ****************************************/

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return mixed
     */
    public function getPrevVersions()
    {
        return $this->prevVersions;
    }

    /**
     * @param AgendaItem $prevVersion
     */
    public function addPrevVersion(AgendaItem $prevVersion)
    {
        $this->prevVersions[] = $prevVersion;
    }

    /**
     * @param AgendaItem $prevVersion
     */
    public function removePrevVersion(AgendaItem $prevVersion)
    {
        $this->prevVersions->removeElement($prevVersion);
    }

    /**
     * @return mixed
     */
    public function getReplacedBy()
    {
        return $this->replacedBy;
    }

    /**
     * @param mixed $replacedBy
     */
    public function setReplacedBy($replacedBy)
    {
        $this->replacedBy = $replacedBy;
    }


//////////////////////////

    /**
     * @return ArrayCollection
     */
    public function getNextVersions()
    {
        return $this->nextVersions;
    }

    /**
     * @param AgendaItem $nextVersion
     */
    public function addNextVersion(AgendaItem $nextVersion)
    {
        $this->nextVersions[] = $nextVersion;
    }

    /**
     * @param AgendaItem $nextVersion
     */
    public function removeNextVersion(AgendaItem $nextVersion)
    {
        $this->nextVersions->removeElement($nextVersion);
    }

    /**
     * @return AgendaItem
     */
    public function getUpdateFor()
    {
        return $this->updateFor;
    }

    /**
     * @param AgendaItem $updateFor
     */
    public function setUpdateFor($updateFor)
    {
        $this->updateFor = $updateFor;
    }


    /**
     * @return Meeting
     */
    public function getPostponedTo()
    {
        return $this->postponedTo;
    }

    /**
     * @param Meeting $postponedTo
     */
    public function setPostponedTo($postponedTo)
    {
        $this->postponedTo = $postponedTo;
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

}
