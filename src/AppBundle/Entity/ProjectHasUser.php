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
     * Many entries can have one user
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id"), referencedColumnName="id"
     * @var User $user
     */
    private $user;

    /**
     * Many entries can have a one project
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * @var Project $project
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="ProjectRole", inversedBy="projectsHaveUsers")
     * @ORM\JoinColumn(name="project_role_id", referencedColumnName="id")
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