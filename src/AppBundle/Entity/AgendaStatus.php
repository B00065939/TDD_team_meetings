<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * AgendaStatus
 *
 * @ORM\Table(name="agenda_status")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AgendaStatusRepository")
 */
class AgendaStatus
{
    function __construct()
    {
        $this->agendaItems = new ArrayCollection();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var ArrayCollection $agendaItems
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AgendaItem", mappedBy="status")
     */
    private $agendaItems;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getAgendaItems()
    {
        return $this->agendaItems;
    }

    /**
     * @param ArrayCollection $agendaItems
     */
    public function setAgendaItems($agendaItems)
    {
        $this->agendaItems = $agendaItems;
    }


    /**
     * Set name
     *
     * @param string $name
     *
     * @return AgendaStatus
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return AgendaStatus
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add agendaItem
     *
     * @param \AppBundle\Entity\AgendaItem $agendaItem
     *
     * @return AgendaStatus
     */
    public function addAgendaItem(\AppBundle\Entity\AgendaItem $agendaItem)
    {
        $this->agendaItems[] = $agendaItem;

        return $this;
    }

    /**
     * Remove agendaItem
     *
     * @param \AppBundle\Entity\AgendaItem $agendaItem
     */
    public function removeAgendaItem(\AppBundle\Entity\AgendaItem $agendaItem)
    {
        $this->agendaItems->removeElement($agendaItem);
    }
}
