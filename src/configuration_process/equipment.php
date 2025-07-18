<?php

namespace configuration_process;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;



#[ORM\Entity]
#[ORM\Table(name: 'equipment')]
class equipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId()
    {
        return $this->id;
    }

  #[ORM\ManyToMany(targetEntity: asset::class, mappedBy: 'asset_equipment')]
    private Collection $bidirectional;

    public function getBidirectional(): Collection
    {
        return $this->bidirectional;
    }



    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($data): void
    {
        $this->description = $data;
    }



    #[ORM\Column(type: 'integer', nullable: true)]
    private int|null $tag_id = null;

    public function getTag()
    {
        return $this->tag_id;
    }

    public function setTag($data): void
    {
        $this->tag_id = $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private   ?string $series;

    public function getSeries()
    {
        return $this->series;
    }

    public function setSeries( $data): void
    {      
        $this->series= $data;
    }  


    #[ORM\Column(type: 'boolean', nullable: true)]
    private $remove;

    public function getRemove()
    {
        return $this->remove;
    }

    public function setRemove($data): void
    {
        $this->remove = $data;
    }




    #[ORM\JoinTable(name: 'equipment_part')]
    #[ORM\JoinColumn(name: 'equipment_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'part_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: part::class)]
    private Collection $equipment_part;

    public function getEquipmentpart()
    {
        return $this->equipment_part;
    }

    public function setEquipmentpart($equipment): void
    {
        if (!$this->equipment_part->contains($equipment)) {
            $this->equipment_part->add($equipment);
        }
    }

    public function removeEquipmentpart($equipments, $data)
    {
        foreach ($equipments as $equipment) {
            if ($this->equipment_part->contains($data)) {
                $this->equipment_part->removeElement($data);
            }
        }
        return $equipments;
    }


    public function __construct()
    {

        $this->equipment_part = new ArrayCollection();
 	$this->bidirectional = new ArrayCollection(); 
   }
}
