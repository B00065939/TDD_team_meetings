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
        $this->agendaItems = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var integer $id
     */
    private $id;

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
     * @ORM\Column(type="string")
     * @var string $location
     */
    private $location;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $mDateTime;

    /**
     * @ORM\Column(type="integer")
     * @var integer $duration
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $agendaDeadline;

    /**
     * @var Project $project
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="meetings")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="MeetingStatus",inversedBy="projects")
     * @ORM\JoinColumn(name="meeting_status_id", referencedColumnName="id")
     * @var MeetingStatus $meetingStatus
     */
    private $meetingStatus;

    /**
     * Collection of all Agenda Items
     * @var ArrayCollection $agendaItems
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AgendaItem", mappedBy="meeting")
     */
    private $agendaItems;

    /**
     * Collection of all meeting attendances
     * @var ArrayCollection $meetingAttendances
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MeetingAttendance", mappedBy="meeting")
     */
    private $meetingAttendances;

    /**
     * Collection of Postponed Agenda Items for this meeting
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AgendaItem", mappedBy="postponedTo")
     */
    private $postponedAgendaItems;


    /******************** GETTERS SETTERS **********************************/

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
     * @return User
     */
    public function getChair()
    {
        return $this->chair;
    }

    /**
     * @param User $chair
     */
    public function setChair(User $chair)
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
    public function setSecretary(User $secretary)
    {
        $this->secretary = $secretary;
    }

    /**
     * @return \DateTime
     */
    public function getMDateTime()
    {
        return $this->mDateTime;
    }

    /**
     * @param \DateTime $mDateTime
     */
    public function setMDateTime(\DateTime $mDateTime)
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
     * @return \DateTime
     */
    public function getAgendaDeadline()
    {
        return $this->agendaDeadline;
    }

    /**
     * @param \DateTime $agendaDeadline
     */
    public function setAgendaDeadline(\DateTime $agendaDeadline)
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
    public function setMeetingStatus(MeetingStatus $meetingStatus)
    {
        $this->meetingStatus = $meetingStatus;
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

    ///////////////////////////////////////////////////////////////////////////////


    /**
     * @return ArrayCollection
     */
    public function getMeetingAttendances()
    {
        return $this->meetingAttendances;
    }

    /**
     * @param MeetingAttendance $meetingAttendance
     */
    public function addMeetingAttendance(MeetingAttendance $meetingAttendance)
    {
        $this->meetingAttendances[] = $meetingAttendance;
    }

    /**
     * @param MeetingAttendance $meetingAttendance
     */
    public function removeMeetingAttendance(MeetingAttendance $meetingAttendance)
    {
        $this->meetingAttendances->removeElement($meetingAttendance);
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @return ArrayCollection
     */
    public function getPostponedAgendaItems()
    {
        return $this->postponedAgendaItems;
    }

    /**
     * @param AgendaItem $postponedAgendaItem
     */
    public function addPostponedAgendaItem(AgendaItem $postponedAgendaItem)
    {
        $this->postponedAgendaItems[] = $postponedAgendaItem;
    }

    /**
     * Remove postponed Agenda Item
     *
     * @param AgendaItem $postponedAgendaItem
     */
    public function removePostponedAgendaItem(AgendaItem $postponedAgendaItem)
    {
        $this->postponedAgendaItems->removeElement($postponedAgendaItem);
    }

//////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @return ArrayCollection
     */
    public function getAgendaItems()
    {
        return $this->agendaItems;
    }

    /**
     * @param AgendaItem $agendaItem
     */
    public function addAgendaItem(AgendaItem $agendaItem)
    {
        $this->agendaItems[] = $agendaItem;
    }

    /**
     * Remove agendaItem
     *
     * @param AgendaItem $agendaItem
     */
    public function removeAgendaItem(AgendaItem $agendaItem)
    {
        $this->agendaItems->removeElement($agendaItem);
    }

//////////////////////////////////////////////////////////////////////////////////////////////////
    function __toString()
    {
        return $this->getMDateTime()->format('Y:m:D HH:MM') . " - " .  $this->getLocation();
    }
}
