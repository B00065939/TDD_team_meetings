<?php
/**
 * Created by PhpStorm.
 * User: bemben
 * Date: 11/04/2017
 * Time: 12:21
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class MeetingStatus
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="meeting_status")
 */
class MeetingStatus
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
     * @var string $status
     */
    private $status;

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
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }


}