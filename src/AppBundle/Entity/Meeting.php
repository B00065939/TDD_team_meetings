<?php
/**
 * Created by PhpStorm.
 * User: bemben
 * Date: 11/04/2017
 * Time: 12:11
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Class Meeting
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MeetingRepository")
 * @ORM\Table(name="meeting")
 */
class Meeting
{
    function __construct()
    {
        $this->meetingAttendances = new ArrayCollection();
        $this->agendas = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var integer $id
     */
    private $id;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AgendaItem", mappedBy="meeting")
     */
    private $agendas;

    /**
     * @var Project $project
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="meetings")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

    /**
     * @var ArrayCollection $meetingAttendances
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MeetingAttendance", mappedBy="meeting")
     */
    private $meetingAttendances;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AgendaItem", mappedBy="postponedTo")
     */
    private $postponedAgendaItems;
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @var User $chair
     */
    private $chair;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @var User $secretary
     */
    private $secretary;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $mDateTime;

    /**
     * @ORM\Column(type="integer")
     * @var integer $duration
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $agendaDeadline;

    /**
     * @var ArrayCollection $agendaItems
     */
    private $agendaItems;

    /**
     * @ORM\ManyToOne(targetEntity="MeetingStatus",inversedBy="projects")
     * @ORM\JoinColumn(name="meeting_status_id", referencedColumnName="id")
     * @var MeetingStatus $meetingStatus
     */
    private $meetingStatus;

    /**
     * @ORM\Column(type="string")
     * @var string $location
     */
    private $location;

    /////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @return string
     */
     public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

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
     * @return User
     */
    public function getChair()
    {
        return $this->chair;
    }

    /**
     * @param User $chair
     */
    public function setChair($chair)
    {
        $this->chair = $chair;
    }

    /**
     * @return mixed
     */
    public function getSecretary()
    {
        return $this->secretary;
    }

    /**
     * @param mixed $secretary
     */
    public function setSecretary($secretary)
    {
        $this->secretary = $secretary;
    }

    /**
     * @return DateTime
     */
    public function getMDateTime()
    {
        return $this->mDateTime;
    }

    /**
     * @param DateTime $mDateTime
     */
    public function setMDateTime($mDateTime)
    {
        $this->mDateTime = $mDateTime;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return DateTime
     */
    public function getAgendaDeadline()
    {
        return $this->agendaDeadline;
    }

    /**
     * @param DateTime $agendaDeadline
     */
    public function setAgendaDeadline($agendaDeadline)
    {
        $this->agendaDeadline = $agendaDeadline;
    }

    /**
     * @return MeetingStatus
     */
    public function getMeetingStatus()
    {
        return $this->meetingStatus;
    }

    /**
     * @param MeetingStatus $meetingStatus
     */
    public function setMeetingStatus($meetingStatus)
    {
        $this->meetingStatus = $meetingStatus;
    }

    /**
     * @return ArrayCollection
     */
    public function getAgendaItems()
    {
        return $this->agendaItems;
    }

    /**
     * @param ArrayCollection $agendaItems
     */
    public function setAgendaItems($agendaItems)
    {
        $this->agendaItems = $agendaItems;
    }

    /**
     * Set project
     *
     * @param Project $project
     *
     * @return Meeting
     */
    public function setProject(Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return ArrayCollection
     */
    public function getMeetingAttendances()
    {
        return $this->meetingAttendances;
    }

    /**
     * @param ArrayCollection $meetingAttendances
     */
    public function setMeetingAttendances($meetingAttendances)
    {
        $this->meetingAttendances = $meetingAttendances;
    }

    /**
     * @return ArrayCollection
     */
    public function getAgendas()
    {
        return $this->agendas;
    }

    /**
     * @param ArrayCollection $agendas
     */
    public function setAgendas($agendas)
    {
        $this->agendas = $agendas;
    }

    /**
     * @return ArrayCollection
     */
    public function getPostponedAgendaItems()
    {
        return $this->postponedAgendaItems;
    }

    /**
     * @param ArrayCollection $postponedAgendaItems
     */
    public function setPostponedAgendaItems($postponedAgendaItems)
    {
        $this->postponedAgendaItems = $postponedAgendaItems;
    }


}
