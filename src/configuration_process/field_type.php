<?php
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'field_type')]
class field_type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;


    public function getId(): int
    {
        return $this->id;
    }
    
    #[ORM\Column(type: 'string',nullable:true)]
    private $description;

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $data): void
    {      
        $this->description= $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $icon;

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $data): void
    {      
        $this->icon= $data;
    }


    #[ORM\Column(type: 'string',nullable:true)]
    private $label;

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $data): void
    {      
        $this->label= $data;
    }




}