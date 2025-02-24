<?php
namespace configuration_process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'platform')]
class platform
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;


    public function getId()
    {
        return $this->id;
    }
    
    
    #[ORM\Column(type: 'string',nullable:true)]
    private $description;

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription( $data): void
    {      
        $this->description= $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $icon;

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon( $data): void
    {      
        $this->icon= $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $label;

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel( $data): void
    {      
        $this->label= $data;
    }

    #[ORM\JoinTable(name: 'platform_platform')]
    #[ORM\JoinColumn(name: 'platform_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'platform_related_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: platform::class)]
    private Collection $platform_link;

    public function getPlatformlink()
    {
        return $this->platform_link;
    }
    public function setPlatformlink( $data): void
    {
        $this->platform_link->add($data);
    }    

    public function __construct()
    {
        $this->platform_link = new ArrayCollection();
    }


}
