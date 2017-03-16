<?php
/**
 * Created by PhpStorm.
 * User: Bemben
 * Date: 15/03/2017
 * Time: 17:27
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Class Project
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="project")
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var integer $id
     */
    private $id;

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




}