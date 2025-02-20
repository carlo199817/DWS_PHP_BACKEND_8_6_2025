<?php
namespace ClientDb\Process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use MainDB\Configuration\store;
use MainDB\Configuration\user;

#[ORM\Entity]
#[ORM\Table(name: 'automation_itinerary')]
class automation_itinerary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId(): int
    {
        return $this->id;
    }
    
    
    #[ORM\ManyToOne(targetEntity: itinerary_type::class, inversedBy:"itinerary_type")]
    #[ORM\JoinColumn(name: 'itinerary_type_id', referencedColumnName: 'id')]
    private itinerary_type|null $itinerary_type_id = null;

    public function getItinerarytype()
    {
        return $this->itinerary_type_id;
    }

    public function setItinerarytype(itinerary_type $data): void
    {
      $this->itinerary_type_id = $data;
    }

    #[ORM\ManyToOne(targetEntity: store::class, inversedBy:"store")]
    #[ORM\JoinColumn(name: 'store_id', referencedColumnName: 'id')]
    private store|null $store_id = null;

    public function getStore()
    {
        return $this->store_id;
    }

    public function setStore(store $data): void
    {
      $this->store_id = $data;
    }





    #[ORM\Column(type: 'text',nullable:true)]
    private string $justification;

    public function getJustification(): string
    {
        return $this->justification;
    }

    public function setJustification(string $data): void
    {      
        $this->justification= $data;
    }


    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $schedule;

    public function setSchedule(DateTime $data): void
    {
        $this->schedule=$data;
    }
    public function getSchedule():DateTime
    {
        return $this->schedule;
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