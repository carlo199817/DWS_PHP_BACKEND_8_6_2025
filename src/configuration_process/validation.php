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

  
    #[ORM\Column(type: 'integer', nullable:true)]
    private int|null $created_by = null;

    public function getCreatedby()
    {
        return $this->created_by;
    }

    public function setCreatedby( $data): void
    {
      $this->created_by=$data;
    }

 
    #[ORM\Column(type: 'integer', nullable:true)]
    private int|null $user_type_id = null;
    
    public function getUsertype()
    {
        return $this->user_type_id;
    }
    
    public function setUsertype( $data): void
    {
      $this->user_type_id=$data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $signature;

    public function getSignature()
    {return $this->signature;}

    public function setSignature( $data): void
    {$this->signature = $data;}

    #[ORM\Column(type: 'text',nullable:true)]
    private $validation_remark;

    public function getValidationremark()
    {return $this->validation_remark;}

    public function setValidationremark( $data): void
    {$this->validation_remark = $data;}

    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $path_id = null;

    public function getPath()
    {
        return $this->path_id;
    }

    public function setPath( $data): void
    {
        $this->path_id= $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $name;

    public function getName()
    {return $this->name;}

    public function setName( $data): void
    {$this->name = $data;}

    #[ORM\Column(type:"datetime",nullable:true)]
    private $date_created;

    public function setDatecreated( $data): void
    {
        $this->date_created=$data;
    }
    public function getDatecreated()
    {
        return $this->date_created;
    }

}
