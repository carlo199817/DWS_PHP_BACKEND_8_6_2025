<?php
namespace process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'user_tracker')]
class user_tracker
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId(): int
    {
        return $this->id;
    }

    #[ORM\Column(type: 'float', nullable:true)]
    private $latitude;

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLatitude($data): void
    {
        $this->latitude=$data;
    }

    #[ORM\Column(type: 'float', nullable:true)]
    private $longitude;

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function setLongitude($data): void
    {
        $this->longitude=$data;
    }

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $date_created;

    public function setDatecreated($data): void
    {
        $this->date_created=$data;
    }
    public function getDatecreated()
    {
        return $this->date_created;
    }

    #[ORM\Column(type: 'integer')]
    private int|null $created_by = null;

    public function getCreatedby()
    {
        return $this->created_by;
    }

    public function setCreatedby($data): void
    {
       $this->created_by=$data;
    }
}
