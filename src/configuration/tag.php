<?php
namespace configuration;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'tag')]
class tag
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
    private int|null $store_id = null;

    public function getStore()
    {
        return $this->store_id;
    }

    public function setStore($data): void
    {
        $this->store_id = $data;
    }

    #[ORM\Column(type: 'text',nullable:true)]
    private $description;

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($data): void
    {
        $this->description = $data;
    }


    #[ORM\Column(type: 'string',nullable:true)]
    private $brand;

    public function getBrand()
    {
        return $this->brand;
    }

    public function setBrand($data): void
    {
        $this->brand = $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $model;

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($data): void
    {
        $this->model = $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $serial;

    public function getSerial()
    {
        return $this->serial;
    }

    public function setSerial($data): void
    {
        $this->serial = $data;
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

    #[ORM\ManyToOne(targetEntity: user::class, inversedBy:"users")]
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
