<?php
/**
 * Created by PhpStorm.
 * User: bemben
 * Date: 20/04/2017
 * Time: 09:09
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ActionStatus
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="action_status")
 */
class ActionStatus
{
    function __construct()
    {
        $this->minuteActions = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var integer $id
     */
    private $id;

    /**
     * @var string $name
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var ArrayCollection $minuteActions
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MinuteAction", mappedBy="status")
     */
    private $minuteActions;

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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return ArrayCollection
     */
    public function getMinuteActions()
    {
        return $this->minuteActions;
    }

    /**
     * @param MinuteAction $minuteAction
     */
    public function addMinuteAction($minuteAction)
    {
        $this->minuteActions[] = $minuteAction;
    }

}