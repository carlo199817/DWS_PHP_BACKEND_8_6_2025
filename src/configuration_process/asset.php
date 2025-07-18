<?php
namespace configuration_process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;



#[ORM\Entity]
#[ORM\Table(name: 'asset')]
class asset
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId()
    {
        return $this->id;
    }

    #[ORM\Column(type: 'text',nullable:true)]
    private string $description;

    public function getDescription()
    {return $this->description;}

    public function setDescription( $data): void
    {$this->description = $data;}


    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $validator_id = null;

    public function getValidator()
    {
        return $this->validator_id;
    }

    public function setValidator( $data): void
    {
      $this->validator_id = $data;
    }


    #[ORM\Column(type: 'boolean', nullable: true)]
    private $selected;

    public function getSelected()
    {
        return $this->selected;
    }

    public function setSelected($data): void
    {
        $this->selected = $data;
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



    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $assigned_id = null;

    public function getAssigned()
    {
        return $this->assigned_id;
    }

    public function setAssigned( $data): void
    {
        $this->assigned_id= $data;
    }

    #[ORM\Column(type: 'text',nullable:true)]
    private string $remark;

    public function getRemark()
    {return $this->remark;}

    public function setRemark( $data): void
    {$this->remark = $data;}


    #[ORM\JoinTable(name: 'asset_equipment')]
    #[ORM\JoinColumn(name: 'asset_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'equipment', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: equipment::class)]
    private Collection $asset_equipment;

    public function getAssetequipment()
    {
        return $this->asset_equipment;
    }
   

    public function setAssetequipment($equipment): void
{
    if (!$this->asset_equipment->contains($equipment)) {
        $this->asset_equipment->add($equipment);
    }
}


public function removeAssetequipment($assets,$data)
    {
        foreach ($assets as $asset) {
            if ($this->asset_equipment->contains($data)) {
                    $this->asset_equipment->removeElement($data);
            }
        }
       return $assets;
    }  


    public function __construct()
    {
    
        $this->asset_equipment = new ArrayCollection();
    
    }
   
}
