<?php
namespace MainDb\Configuration;
namespace ClientDb\Process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'platform')]
class Platform
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

    #[ORM\JoinTable(name: 'platform_platform')]
    #[ORM\JoinColumn(name: 'platform_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'platform_related_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: platform::class)]
    private Collection $platform_link;

    public function getPlatformlink(): Collection
    {
        return $this->platform_link;
    }
    public function setPlatformlink(platform $data): void
    {
        $this->platform_link->add($data);
    }    

    public function __construct()
    {
        $this->platform_link = new ArrayCollection();
    }


}