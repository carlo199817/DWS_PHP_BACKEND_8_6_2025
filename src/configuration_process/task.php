<?php
namespace MainDb\Configuration;
namespace ClientDb\Process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'task')]
class task
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

    #[ORM\Column(type: 'string',nullable:true)]
    private $title;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $data): void
    {      
        $this->title= $data;
    }

    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $series = null;

    public function getSeries()
    {   if($this->series!=null){
        return $this->series;
        }else{
        return 0;           
        }
    }


    public function setSeries (int $data):void
    {
        $this->series = $data;
    }


    #[ORM\ManyToOne(targetEntity: status::class, inversedBy:"status")]
    #[ORM\JoinColumn(name: 'status_id', referencedColumnName: 'id')]
    private status|null $status_id = null;

    
    public function getStatus()
    {
        return $this->status_id;
    }

    public function setStatus(status $data): void
    {
      $this->status_id=$data;
    }



    #[ORM\JoinTable(name: 'task_validation')]
    #[ORM\JoinColumn(name: 'task_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'validation_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: validation::class)]
    private Collection $task_validation;

    public function getTaskvalidation(): Collection
    {
        return $this->task_validation;
    }
    public function setTaskvalidation(validation $data): void
    {
        $this->task_validation->add($data);
    }

    #[ORM\JoinTable(name: 'task_field')]
    #[ORM\JoinColumn(name: 'task_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'field_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: field::class)]
    private Collection $task_field;

    public function getTaskfield(): Collection
    {
        return $this->task_field;
    }
    public function setTaskfield(field $data): void
    {
        $this->task_field->add($data);
    }

    public function __construct()
    {
        $this->task_field = new ArrayCollection();
        $this->task_validation = new ArrayCollection();
    }



}