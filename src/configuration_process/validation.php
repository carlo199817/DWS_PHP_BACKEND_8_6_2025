<?php
namespace configuration_process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use configuration\user;



#[ORM\Entity]
#[ORM\Table(name: 'validation')]
class validation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

  
    public function getId()
    {
        return $this->id;
    }
    
    
    #[ORM\Column(type: 'boolean', nullable:true)]
    private $valid;

    public function getValid()
    {
        return $this->valid;
    }

    public function setValid( $data): void
    {      
        $this->valid=$data;
    }

    #[ORM\ManyToOne(targetEntity: user::class, inversedBy:"user")]
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'id')]
    private user|null $created_by = null;

    
    public function getCreatedby()
    {
        return $this->created_by;
    }

    public function setCreatedby( $data): void
    {
      $this->created_by=$data;
    }

    #[ORM\ManyToOne(targetEntity: user_type::class, inversedBy:"user_type")]
    #[ORM\JoinColumn(name: 'user_type_id', referencedColumnName: 'id')]
    private user_type|null $user_type_id = null;
    
    public function getUsertype()
    {
        return $this->user_type_id;
    }
    
    public function setUsetype( $data): void
    {
      $this->user_type_id=$data;
    }




}
