<?php
/**
 * Created by PhpStorm.
 * User: bemben
 * Date: 11/04/2017
 * Time: 12:11
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Time;

/**
 * Class Meeting
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="meeting")
 */
class Meeting
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var integer $id
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\Column(type="")
     * @var User $chair
     */
    private $chair;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MeetingStatus")
     * @var MeetingStatus $meetingStatus
     */
    private $meetingStatus;

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
     * @ORM\Column(type="string")
     * @var string $location
     */
    private $location;

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


}