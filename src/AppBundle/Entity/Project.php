<?php
/**
 * Created by PhpStorm.
 * User: Bemben
 * Date: 15/03/2017
 * Time: 17:27
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Project
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 * @ORM\Table(name="project")
 */
class Project

{
    function __construct()
    {
        $this->meetings = new ArrayCollection();
        $this->projectUsers = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var integer $id
     */
    private $id;

    /**
     *
     * @var ArrayCollection $meetings
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Meeting", mappedBy="project")
     */
    private $meetings;

    /**
     * @ORM\Column(type="string")
     * @var String $title
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean $lock
     */
    private $locked = false;

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
     * @return String
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param String $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return bool
     */
    public function isLock()
    {
        return $this->locked;
    }

    /**
     * @param bool $locked
     */
    public function setLock($locked)
    {
        $this->locked = $locked;
    }

    function __toString()
    {
        return $this->getTitle();
    }


    /**
     * Add meeting
     *
     * @param Meeting $meeting
     *
     * @return Project
     */
    public function addMeeting(Meeting $meeting)
    {
        $this->meetings[] = $meeting;

        return $this;
    }

    /**
     * Remove meeting
     *
     * @param Meeting $meeting
     */
    public function removeMeeting(Meeting $meeting)
    {
        $this->meetings->removeElement($meeting);
    }

    /**
     * Get meetings
     *
     * @return ArrayCollection
     */
    public function getMeetings()
    {
        return $this->meetings;
    }
}
