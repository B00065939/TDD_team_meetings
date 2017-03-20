<?php
/**
 * Created by PhpStorm.
 * User: Bemben
 * Date: 16/03/2017
 * Time: 11:26
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ProjectHasUser
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectHasUserRepository")
 * @ORM\Table(name="project_has_user")
 */
class ProjectHasUser
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
     * @var User $user
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @var Project $project
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="ProjectRole")
     * @var ProjectRole $projectRole
     */
    private $projectRole;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return mixed
     */
    public function getProjectRole()
    {
        return $this->projectRole;
    }

    /**
     * @param mixed $projectRole
     */
    public function setProjectRole($projectRole)
    {
        $this->projectRole = $projectRole;
    }




}