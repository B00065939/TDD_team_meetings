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
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var integer $id
     */
    private $id;

    /**
     * @var Meeting $meeting
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Meeting", inversedBy="agendas")
     * @ORM\JoinColumn(name="meeting_id", referencedColumnName="id")
     */
    private $meeting;

    /**
     * One agenda item has one lestVersion agenda item
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\AgendaItem")
     * @ORM\JoinColumn(name="last_version_id", referencedColumnName="id")
     */
    private $lastVersion;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\AgendaItem")
     * @ORM\JoinColumn(name="next_version_id", referencedColumnName="id")
     */
    private $nextVersion;

    /**
     * @var string $title
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Meeting", inversedBy="postponedAgendaItems")
     * @ORM\JoinColumn(name="meeting_id", referencedColumnName="id")
     * @var Meeting $postponedTo
     */
    private $postponedTo;

    /**
     * @var int $sequenceNo
     */
    private $sequenceNo;

    /**
     * @var User $proposer
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="agendaItems")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $proposer;

    /**
     * @var \DateTime $creationDate
     * @ORM\Column(name="creation_date", type="datetime")
     */
    private $creationDate;

    /**
     * @var AgendaStatus $status
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\AgendaStatus", inversedBy="agendaItems")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;



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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return AgendaItem
     */
    public function setCreationDate(\DateTime $creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set meeting
     *
     * @param Meeting $meeting
     *
     * @return AgendaItem
     */
    public function setMeeting(Meeting $meeting = null)
    {
        $this->meeting = $meeting;

        return $this;
    }

    /**
     * Get meeting
     *
     * @return Meeting
     */
    public function getMeeting()
    {
        return $this->meeting;
    }

    /**
     * Set lastVersion
     *
     * @param AgendaItem $lastVersion
     *
     * @return AgendaItem
     */
    public function setLastVersion(AgendaItem $lastVersion = null)
    {
        $this->lastVersion = $lastVersion;

        return $this;
    }

    /**
     * Get lastVersion
     *
     * @return AgendaItem
     */
    public function getLastVersion()
    {
        return $this->lastVersion;
    }

    /**
     * Set nextVersion
     *
     * @param AgendaItem $nextVersion
     *
     * @return AgendaItem
     */
    public function setNextVersion(AgendaItem $nextVersion = null)
    {
        $this->nextVersion = $nextVersion;

        return $this;
    }

    /**
     * Get nextVersion
     *
     * @return AgendaItem
     */
    public function getNextVersion()
    {
        return $this->nextVersion;
    }

    /**
     * Set postponedTo
     *
     * @param Meeting $postponedTo
     *
     * @return AgendaItem
     */
    public function setPostponedTo(Meeting $postponedTo = null)
    {
        $this->postponedTo = $postponedTo;

        return $this;
    }

    /**
     * Get postponedTo
     *
     * @return Meeting
     */
    public function getPostponedTo()
    {
        return $this->postponedTo;
    }

    /**
     * Set proposer
     *
     * @param User $proposer
     *
     * @return AgendaItem
     */
    public function setProposer(User $proposer = null)
    {
        $this->proposer = $proposer;

        return $this;
    }

    /**
     * Get proposer
     *
     * @return User
     */
    public function getProposer()
    {
        return $this->proposer;
    }

    /**
     * Set status
     *
     * @param AgendaStatus $status
     *
     * @return AgendaItem
     */
    public function setStatus(AgendaStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return AgendaStatus
     */
    public function getStatus()
    {
        return $this->status;
    }
}
