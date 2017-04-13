<?php
/**
 * Created by PhpStorm.
 * User: Bemben
 * Date: 16/03/2017
 * Time: 11:50
 */

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ProjectRole
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRoleRepository")
 */
class ProjectRole
{
    function __construct()
    {
        $this->projectsHaveUsers = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var integer $id
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var String $name
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjectHasUser", mappedBy="projectRole")
     * @var ArrayCollection $projectsHaveUsers
     */
    private $projectsHaveUsers;
    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param String $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    function __toString()
    {
        return $this->getName();
    }


}