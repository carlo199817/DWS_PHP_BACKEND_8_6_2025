<?php
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'plot')]
class plot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;


    public function getId(): int
    {
        return $this->id;
    }
    
    #[ORM\ManyToOne(targetEntity: field::class, inversedBy:"field")]
    #[ORM\JoinColumn(name: 'field_id', referencedColumnName: 'id')]
    private field|null $field_id = null;

    public function getField()
    {
        return $this->field_id;
    }

    public function setField(field $data): void
    {
      $this->field_id=$data;
    }




}