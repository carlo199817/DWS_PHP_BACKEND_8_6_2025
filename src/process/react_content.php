<?php
namespace ClientDb\Process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'react_content')]
class react_content
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

   
    public function getId(): int
    {
        return $this->id;
    }
    
    

    
    #[ORM\ManyToOne(targetEntity: react_type::class, inversedBy:"react_type")]
    #[ORM\JoinColumn(name: 'type_id', referencedColumnName: 'id')]
    private react_type|null $type_id = null;

    
    public function getType()
    {
        return $this->type_id;
    }

    public function setType(react_type  $data): void
    {
      $this->type_id=$data;
    }


    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $date_created;

    public function setDatecreated(DateTime $data): void
    {
        $this->date_created=$data;
    }
    public function getDatecreated():DateTime
    {
        return $this->date_created;
    }


    
    #[ORM\ManyToOne(targetEntity: user::class, inversedBy:"user")]
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'id')]
    private user|null $created_by = null;

    public function getCreatedby()
    {
        return $this->created_by;
    }

    public function setCreatedby(user $data): void
    {
      $this->created_by=$data;
    }







}