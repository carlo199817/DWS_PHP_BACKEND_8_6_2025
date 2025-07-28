<?php
namespace process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'node_mcu_record')]
class node_mcu_record
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
    private int|null $node_mcu_id = null;

    public function getNodemcu()
    {
        return $this->node_mcu_id;
    }

    public function setNodemcu($data): void
    {
        $this->node_mcu_id = $data;
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

}
