<?php
namespace ClientDb\Process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'track_itinerary')]
class track_itinerary
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
    private $location;

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $data): void
    {      
        $this->location= $data;
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


}