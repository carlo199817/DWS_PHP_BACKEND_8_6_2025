<?php
namespace MainDb\Configuration;
namespace ClientDb\Process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;



use MainDB\Configuration\user;

#[ORM\Entity]
#[ORM\Table(name: 'justification_form')]
class justification_form
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

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $date_created;

    public function setDateCreated(DateTime $data): void
    {
        $this->date_created=$data;
    }
    public function getDateCreated():DateTime
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