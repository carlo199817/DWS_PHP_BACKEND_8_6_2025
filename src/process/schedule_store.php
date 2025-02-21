<?php
namespace ClientDb\Process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'schedule_store')]
class schedule_store
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId()
    {
        return $this->id;
    }
    

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $date_assigned;

    public function setDateAssigned( $data): void
    {
        $this->date_assigned=$data;
    }
    public function getDateAssigned()
    {
        return $this->date_assigned;
    }


    #[ORM\JoinTable(name: 'schedule_store_store')]
    #[ORM\JoinColumn(name: 'schedule_store_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'store_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: store::class)]
    private Collection $schedule_store_store;

    public function getSchedulestore()
    {
        return $this->schedule_store_store;
    }
    public function setSchedulestore( $data): void
    {
        $this->schedule_store_store->add($data);
    }
    

    public function __construct()
    {
        $this->schedule_store_store = new ArrayCollection();
    }



}