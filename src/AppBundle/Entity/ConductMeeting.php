<?php
/**
 * Created by PhpStorm.
 * User: Bemben
 * Date: 16/03/2017
 * Time: 11:50
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ConductMeeting
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRoleRepository")
 */
class ConductMeeting
{
    function __construct()
    {
        $this->startDate = new \DateTime();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var integer $id
     */
    private $id;

    /**
     * @var Meeting $meeting
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Meeting", inversedBy="conductMeeting")
     */
    private $meeting;

    /**
     * @var \DateTime $startDate
     */
    private $startDate;

    /**
     * @var AgendaItem $currentAgendaItem
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\AgendaItem")
     */
    private $currentAgendaItem;

    /******************** GETTERS SETTERS **********************************/
    /******************** GETTERS SETTERS **********************************/
    /******************** GETTERS SETTERS **********************************/

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
    public function setMeeting(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return AgendaItem
     */
    public function getCurrentAgendaItem()
    {
        return $this->currentAgendaItem;
    }

    /**
     * @param AgendaItem $currentAgendaItem
     */
    public function setCurrentAgendaItem(AgendaItem $currentAgendaItem)
    {
        $this->currentAgendaItem = $currentAgendaItem;
    }


}