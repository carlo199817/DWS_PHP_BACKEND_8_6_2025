<?php
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

    public function getOutletifs(): string
    {   
        return $this->outlet_ifs; 
    }

    public function setOutletifs(string $data): void
    {      
        $this->outlet_ifs = $data;
    }


    
    #[ORM\Column(type: 'string',nullable:true)]
    private string $outlet_code;

    public function getOutletcode(): string
    {   
        return $this->outlet_code; 
    }

    public function setOutletcode(string $data): void
    {      
        $this->outlet_code = $data;
    }

    
    #[ORM\Column(type: 'string',nullable:true)]
    private string $town;

    public function getTown(): string
    {   
        return $this->town; 
    }

    public function setTown(string $data): void
    {      
        $this->town = $data;
    }


    #[ORM\Column(type: 'string',nullable:true)]
    private string $zip_code;

    public function getZipcode(): string
    {   
        return $this->zip_code; 
    }

    public function setZipcode(string $data): void
    {      
        $this->zip_code = $data;
    }


    #[ORM\Column(type: 'string',nullable:true)]
    private string $outlet_name;

    public function getOutletname(): string
    {   
        return $this->outlet_name; 
    }

    public function setOutletname(string $data): void
    {      
        $this->outlet_name = $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private string $address;

    public function getAddress(): string
    {   
        return $this->address; 
    }

    public function setAddress(string $data): void
    {      
        $this->address = $data;
    }

    #[ORM\Column(type: 'decimal', nullable:true)]
    private ?string $latitude;

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $data): void
    {      
        $this->latitude=$data;
    }

    
    #[ORM\Column(type: 'decimal', nullable:true)]
    private ?string $longitude;

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $data): void
    {      
        $this->longitude=$data;
    }

    

    #[ORM\Column(type: 'decimal', nullable:true)]
    private ?string $distance;

    public function getDistance(): ?string
    {
        return $this->distance;
    }

    public function setDistance(?string $data): void
    {      
        $this->distance=$data;
    }
    

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $start_time;

    public function setStarttime(DateTime $data): void
    {
        $this->start_time=$data;
    }
    public function getStarttime():DateTime
    {
        return $this->start_time;
    }

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $end_time;

    public function setEndtime(DateTime $data): void
    {
        $this->end_time=$data;
    }
    public function getEndtime():DateTime
    {
        return $this->end_time;
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