<?php
namespace configuration_process;
use process\store;
use process\user;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;



#[ORM\Entity]
#[ORM\Table(name: 'itinerary')]
class itinerary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId()
    {
        return $this->id;
    }


    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $type_id = null;

    public function getType()
    {
        return $this->type_id;
    }

    public function setType( $data): void
    {
      $this->type_id = $data;
    }

    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $store_id = null;

    public function getStore()
    {
        return $this->store_id;
    }

    public function setStore( $data): void
    {
        $this->store_id= $data;
    }

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

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $schedule;

    public function setSchedule( $data): void
    {
        $this->schedule = $data;
    }
    public function getSchedule()
    {
        return $this->schedule;
    }


    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $check_in_time;

    public function setCheckintime($data): void
    {
        $this->check_in_time = $data;
    }
    public function getCheckintime()
    {
        return $this->check_in_time;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $check_in_image;

    public function getCheckinimage()
    {
        return $this->check_in_image;
    }

    public function setCheckinimage( $data): void
    {
        $this->check_in_image= $data;
    }


    #[ORM\Column(type: 'float',nullable:true)]
    private string $check_in_latitude;

    public function getCheckinlatitude()
    {
        return $this->check_in_latitude;
    }

    public function setCheckinlatitude( $data): void
    {
        $this->check_in_latitude= $data;
    }

    #[ORM\Column(type: 'float',nullable:true)]
    private string $check_in_longitude;

    public function getCheckinlongitude()
    {
        return $this->check_in_longitude;
    }

    public function setCheckinlongitude( $data): void
    {
        $this->check_in_longitude= $data;
    }

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $check_out_time;

    public function setCheckouttime( $data): void
    {
        $this->check_out_time = $data;
    }
    public function getCheckouttime()
    {
        return $this->check_out_time;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $check_out_image;

    public function getCheckoutimage()
    {
        return $this->check_out_image;
    }

    public function setCheckoutimage( $data): void
    {
        $this->check_out_image= $data;
    }


    #[ORM\Column(type: 'float',nullable:true)]
    private string $check_out_latitude;

    public function getCheckoutlatitude()
    {
        return $this->check_out_latitude;
    }

    public function setCheckoutlatitude( $data): void
    {
        $this->check_out_latitude= $data;
    }

    #[ORM\Column(type: 'float',nullable:true)]
    private string $check_out_longitude;

    public function getCheckoutlongitude()
    {
        return $this->check_out_longitude;
    }

    public function setCheckoutlongitude( $data): void
    {
        $this->check_out_longitude= $data;
    }

    #[ORM\Column(type: 'text',nullable:true)]
    private string $itinerary_remark;

    public function getItineraryremark()
    {return $this->itinerary_remark;}

    public function setItineraryremark( $data): void
    {$this->itinerary_remark = $data;}


    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $date_created;

    public function setDateCreated( $data): void
    {
        $this->date_created=$data;
    }
    public function getDateCreated()
    {
        return $this->date_created;
    }

    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $created_by = null;

    public function getCreatedBy()
    {
        return $this->created_by;
    }
    public function setCreatedBy( $data): void
    {
        $this->created_by=$data;
    }

    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $assigned_to = null;

    public function getAssignedto()
    {
        return $this->assigned_to;
    }

    public function setAssignedto($data): void
    {
        $this->assigned_to= $data;
    }

    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $approved_by = null;

    public function getApprovedby()
    {
        return $this->approved_by;
    }

    public function setApprovedby($data): void
    {
        $this->approved_by= $data;
    }

    #[ORM\JoinTable(name: 'itinerary_justification_itinerary')]
    #[ORM\JoinColumn(name: 'itinerary_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'justification_itinerary_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: justification_itinerary::class)]
    private Collection $itinerary_justification;

    public function getItineraryjustification()
    {
        return $this->itinerary_justification;
    }
    public function setItineraryjustification( $data): void
    {
        $this->itinerary_justification->add($data);
    }

    #[ORM\JoinTable(name: 'itinerary_reform')]
    #[ORM\JoinColumn(name: 'itinerary_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'reform_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: reform::class)]
    private Collection $itinerary_reform;

    public function getItineraryreform()
    {
        return $this->itinerary_reform;
    }
    public function setItineraryreform( $data): void
    {
        $this->itinerary_reform->add($data);
    }

    #[ORM\JoinTable(name: 'itinerary_form')]
    #[ORM\JoinColumn(name: 'itinerary_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: form::class)]
    private Collection $itinerary_form;

    public function getItineraryform()
    {
        return $this->itinerary_form;
    }
    public function setItineraryform( $data): void
    {
        $this->itinerary_form->add($data);
    }

    #[ORM\JoinTable(name: 'itinerary_asset')]
    #[ORM\JoinColumn(name: 'itinerary_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'asset_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: asset::class)]
    private Collection $itinerary_asset;

    public function getItineraryasset()
    {
        return $this->itinerary_asset;
    }
    public function setItineraryasset( $data): void
    {
        $this->itinerary_asset->add($data);
    }

    public function __construct()
    {
        $this->itinerary_reform = new ArrayCollection();
        $this->itinerary_justification = new ArrayCollection();
        $this->itinerary_form = new ArrayCollection();
        $this->itinerary_tracker_itinerary = new ArrayCollection();
        $this->itinerary_asset = new ArrayCollection();
    }
}
