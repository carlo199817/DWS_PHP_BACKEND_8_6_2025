<?php
namespace configuration;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'store')]
class store
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
    private string $outlet_ifs;

    public function getOutletifs()
    {   
        return $this->outlet_ifs; 
    }

    public function setOutletifs($data): void
    {      
        $this->outlet_ifs = $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private string $outlet_code;

    public function getOutletcode()
    {   
        return $this->outlet_code; 
    }

    public function setOutletcode($data): void
    {      
        $this->outlet_code = $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private string $town;

    public function getTown()
    {   
        return $this->town; 
    }

    public function setTown($data): void
    {      
        $this->town = $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private string $zip_code;

    public function getZipcode()
    {   
        return $this->zip_code; 
    }

    public function setZipcode($data): void
    {      
        $this->zip_code = $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private string $outlet_name;

    public function getOutletname()
    {   
        return $this->outlet_name; 
    }

    public function setOutletname($data): void
    {      
        $this->outlet_name = $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private string $address;

    public function getAddress()
    {   
        return $this->address; 
    }

    public function setAddress($data): void
    {      
        $this->address = $data;
    }

    #[ORM\Column(type: 'decimal', nullable:true)]
    private ?string $latitude;

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLatitude($data): void
    {      
        $this->latitude=$data;
    }

    
    #[ORM\Column(type: 'decimal', nullable:true)]
    private ?string $longitude;

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function setLongitude($data): void
    {      
        $this->longitude=$data;
    }

    #[ORM\Column(type: 'decimal', nullable:true)]
    private ?string $distance;

    public function getDistance()
    {
        return $this->distance;
    }

    public function setDistance($data): void
    {      
        $this->distance=$data;
    }
    
    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $start_time;

    public function setStarttime($data): void
    {
        $this->start_time=$data;
    }
    public function getStarttime()
    {
        return $this->start_time;
    }

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $end_time;

    public function setEndtime($data): void
    {
        $this->end_time=$data;
    }
    public function getEndtime()
    {
        return $this->end_time;
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
