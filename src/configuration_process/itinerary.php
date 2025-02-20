<?php
namespace ClientDb\Process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use MainDB\Configuration\store;
use MainDB\Configuration\user;


#[ORM\Entity]
#[ORM\Table(name: 'itinerary')]
class itinerary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId(): int
    {
        return $this->id;
    }
    
    #[ORM\ManyToOne(targetEntity: itinerary_type::class, inversedBy:"store")]
    #[ORM\JoinColumn(name: 'type_id', referencedColumnName: 'id')]
    private itinerary_type|null $type_id = null;


    public function getType()
    {
        return $this->type_id;
    }

    public function setType(itinerary_type $data): void
    {
      $this->type_id = $data;
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
      $this->store_id=$data;
    }


    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $schedule;

    public function setSchedule(DateTime $data): void
    {
        $this->schedule = $data;
    }
    public function getSchedule():DateTime
    {
        return $this->schedule;
    }

    #[ORM\Column(type: 'boolean', nullable:true)]
    private $check_in;

    public function getCheckin(): bool
    {
        return $this->check_in;
    }

    public function setCheckin(bool $data): void
    {      
        $this->check_in=$data;
    }


    
    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $check_in_time;

    public function setCheckintime(DateTime $data): void
    {
        $this->check_in_time = $data;
    }
    public function getCheckintime():DateTime
    {
        return $this->check_in_time;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $check_in_image;

    public function getCheckinimage(): string
    {
        return $this->check_in_image;
    }

    public function setCheckinimage(string $data): void
    {      
        $this->check_in_image= $data;
    }


    #[ORM\Column(type: 'float',nullable:true)]
    private string $check_in_latitude;

    public function getCheckinlatitude(): string
    {
        return $this->check_in_latitude;
    }

    public function setCheckinlatitude(string $data): void
    {      
        $this->check_in_latitude= $data;
    }

    #[ORM\Column(type: 'float',nullable:true)]
    private string $check_in_longitude;

    public function getCheckinlongitude(): string
    {
        return $this->check_in_longitude;
    }

    public function setCheckinlongitude(string $data): void
    {      
        $this->check_in_longitude= $data;
    }

    #[ORM\Column(type: 'text',nullable:true)]
    private string $check_in_remark;

    public function getCheckinremark(): string
    {return $this->check_in_remark;}

    public function setCheckinremark(string $data): void
    {$this->check_in_remark = $data;}








    #[ORM\Column(type: 'boolean', nullable:true)]
    private $check_out;

    public function getCheckout(): bool
    {
        return $this->check_out;
    }

    public function setCheckout(bool $data): void
    {      
        $this->check_out=$data;
    }


    
    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $check_out_time;

    public function setCheckouttime(DateTime $data): void
    {
        $this->check_out_time = $data;
    }
    public function getCheckouttime():DateTime
    {
        return $this->check_out_time;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $check_out_image;

    public function getCheckoutimage(): string
    {
        return $this->check_out_image;
    }

    public function setCheckoutimage(string $data): void
    {      
        $this->check_out_image= $data;
    }


    #[ORM\Column(type: 'float',nullable:true)]
    private string $check_out_latitude;

    public function getCheckoutlatitude(): string
    {
        return $this->check_out_latitude;
    }

    public function setCheckoutlatitude(string $data): void
    {      
        $this->check_out_latitude= $data;
    }

    #[ORM\Column(type: 'float',nullable:true)]
    private string $check_out_longitude;

    public function getCheckoutlongitude(): string
    {
        return $this->check_out_longitude;
    }

    public function setCheckoutlongitude(string $data): void
    {      
        $this->check_out_longitude= $data;
    }

    #[ORM\Column(type: 'text',nullable:true)]
    private string $check_out_remark;

    public function getCheckoutremark(): string
    {return $this->check_out_remark;}

    public function setCheckoutremark(string $data): void
    {$this->check_out_remark = $data;}


    
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

    public function getCreatedBy()
    {
        return $this->created_by;
    }

    public function setCreatedBy(user $data): void
    {
      $this->created_by=$data;
    }


    
    #[ORM\JoinTable(name: 'itinerary_connection_itinerary')]
    #[ORM\JoinColumn(name: 'itinerary_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'connection_itinerary_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: connection_itinerary::class)]
    private Collection $connection_itinerary;

    public function getConnectionitinerary(): Collection
    {
        return $this->connection_itinerary;
    }
    public function setConnectionitinerary(connection_itinerary $data): void
    {
        $this->connection_itinerary->add($data);
    }   

    #[ORM\JoinTable(name: 'itinerary_justification_itinerary')]
    #[ORM\JoinColumn(name: 'itinerary_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'justification_itinerary_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: justification_itinerary::class)]
    private Collection $itinerary_justification;

    public function getItineraryjustification(): Collection
    {
        return $this->itinerary_justification;
    }
    public function setItineraryjustification(justification_itinerary $data): void
    {
        $this->itinerary_justification->add($data);
    }   



    public function __construct()
    {
    
        $this->connection_itinerary = new ArrayCollection();
        $this->itinerary_justification = new ArrayCollection();
    }

        


}