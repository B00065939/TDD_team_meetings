<?php
/**
 * Created by PhpStorm.
 * User: Bemben
 * Date: 17/02/2017
 * Time: 00:06
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User implements UserInterface
{
    function __construct()
    {
        $this->agendaItems = new ArrayCollection();
        $this->minuteItems = new ArrayCollection();
        $this->actionsToDo = new ArrayCollection();
        $this->proposedActions = new ArrayCollection();

//        $this->projects = new ArrayCollection();
    }

//    /**
//      * Many projects has many users
//     * @var ArrayCollection $projects
//     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Project", mappedBy="users")
//      *
//     */
//    private $projects;
    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var String $email
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     * @var String $fullName
     */
    private $fullName;
    /**
     * The encoded password
     *
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var ArrayCollection $agendaItems
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AgendaItem", mappedBy="proposer", cascade={"persist", "remove"})
     */
    private $agendaItems;

    /**
     * @var ArrayCollection $minuteItems
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MinuteItem", mappedBy="proposer", cascade={"persist", "remove"})
     */
    private $minuteItems;

    /**
     * A non-persisted field that's used to create the encoded password.
     *
     * @var string
     */
    private $plainPassword;

    /**
     * @var ArrayCollection $proposedActions
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MinuteAction", mappedBy="proposer", cascade={"persist", "remove"})
     */
    private $proposedActions;

    /**
     * @var ArrayCollection $actionsToDo
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MinuteAction", mappedBy="doer", cascade={"persist", "remove"})
     */
    private $actionsToDo;

    /********************** GETTERS SETTERS ****************************************/
    /********************** GETTERS SETTERS ****************************************/
    /********************** GETTERS SETTERS ****************************************/

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        // forces the object to look "dirty" to Doctrine. Avoids
        // Doctrine *not* saving this entity, if only plainPassword changes
        $this->password = null;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role
     */
    public function getRoles()
    {
        $roles = $this->roles;
//        if ( !in_array('ROLE_USER', $roles)) {
//            $roles[] = 'ROLE_USER';
//        }
        return $roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return integer
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->getFullName() . ' (' . $this->getEmail() . ')';
    }

    /**
     * @return String
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param String $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

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
    public function addAgendaItems(AgendaItem $agendaItem)
    {
        $this->agendaItems[] = $agendaItem;
    }

    public function removeAgendaItem(AgendaItem $agendaItem)
    {
        $this->agendaItems->removeElement($agendaItem);
    }

    /**
     * @return ArrayCollection
     */
    public function getMinuteItems()
    {
        return $this->minuteItems;
    }

    /**
     * @param MinuteItem $minuteItem
     */
    public function addMinuteItems(MinuteItem $minuteItem)
    {
        $this->minuteItems[] = $minuteItem;
    }

    /**
     * @param MinuteItem $minuteItem
     */
    public function removeMinuteItem(MinuteItem $minuteItem)
    {
        $this->minuteItems->removeElement($minuteItem);
    }

    /**
     * @return ArrayCollection
     */
    public function getProposedActions()
    {
        return $this->proposedActions;
    }

    /**
     * @param MinuteAction $proposedAction
     */
    public function addProposedAction(MinuteAction $proposedAction)
    {
        $this->proposedActions[] = $proposedAction;
    }

    /**
     * @param MinuteAction $proposedAction
     */
    public function removeProposedAction(MinuteAction $proposedAction)
    {
        $this->proposedActions->removeElement($proposedAction);
    }

    /**
     * @return ArrayCollection
     */
    public function getActionsToDo()
    {
        return $this->actionsToDo;
    }

    /**
     * @param MinuteAction $actionToDo
     */
    public function addActionToDo(MinuteAction $actionToDo)
    {
        $this->actionsToDo[] = $actionToDo;
    }

    /**
     * @param MinuteAction $actionToDo
     */
    public function removeActionToDo(MinuteAction $actionToDo)
    {
        $this->actionsToDo->removeElement($actionToDo);
    }
}