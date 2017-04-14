<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MeetingAttendance
 *
 * @ORM\Table(name="meeting_attendance")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MeetingAttendanceRepository")
 */
class MeetingAttendance
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Meeting $meeting
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Meeting", inversedBy="meetingAttendances")
     * @ORM\JoinColumn(name="meeting_id", referencedColumnName="id")
     *
     */
    private $meeting;

    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $user;

    /**
     * @var string $attendance
     *
     * @ORM\Column(name="attendance", type="string", length=55, nullable=true)
     */
    private $attendance;

    /**
     * @var string $presence
     * @ORM\Column(name="presence", type="string", length=55, nullable=true)
     */
    private $presence;


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
     * Set attendance
     *
     * @param string $attendance
     *
     * @return MeetingAttendance
     */
    public function setAttendance($attendance)
    {
        $this->attendance = $attendance;

        return $this;
    }

    /**
     * Get attendance
     *
     * @return string
     */
    public function getAttendance()
    {
        return $this->attendance;
    }

    /**
     * Set presence
     *
     * @param string $presence
     *
     * @return string MeetingAttendance
     */
    public function setPresence($presence)
    {
        $this->presence = $presence;

        return $this;
    }

    /**
     * Get presence
     *
     * @return string
     */
    public function getPresence()
    {
        return $this->presence;
    }

    /**
     * Set meeting
     *
     * @param Meeting $meeting
     *
     * @return MeetingAttendance
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return MeetingAttendance
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
