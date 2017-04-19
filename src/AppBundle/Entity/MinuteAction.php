<?php
/**
 * Created by PhpStorm.
 * User: bemben
 * Date: 19/04/2017
 * Time: 15:27
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class MinuteAction
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="minute_action")
 */
class MinuteAction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var integer $id
     */
    private $id;

    /**
     * @var MinuteItem $minuteItem
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MinuteItem", inversedBy="actions")
     * @ORM\JoinColumn(name="minute_item_id", referencedColumnName="id")
     */
    private $minuteItem;


}