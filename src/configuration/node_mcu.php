<?php
namespace configuration;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'node_mcu')]
class node_mcu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId()
    {
        return $this->id;
    }

    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $store_id = null;

    public function getStore()
    {
        return $this->store_id;
    }

    public function setStore($data): void
    {
        $this->store_id = $data;
    }

    #[ORM\Column(type: 'text',nullable:true)]
    private $description;

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($data): void
    {
        $this->description = $data;
    }

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $date_created;

    public function setDatecreated($data): void
    {
        $this->date_created=$data;
    }
    public function getDatecreated()
    {
        return $this->date_created;
    }

    #[ORM\Column(type: 'boolean', nullable:true)]
    private $active;

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($data): void
    {
        $this->active=$data;
    }

}
