<?php
namespace configuration_process;
use configuration\store;
use configuration\user;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;



#[ORM\Entity]
#[ORM\Table(name: 'form')]
class form
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;


 

    public function getId()
    {
        return $this->id;
    }
 
    #[ORM\Column(type: 'string',nullable:true)]
    private $title;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle( $data): void
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


    public function setSeries ( $data):void
    {
        $this->series = $data;
    }
    


    #[ORM\Column(type: 'text',options:["default" => "0.0.0"], nullable:true)]
    private string $version;

    public function getVersion()
    {return $this->version;}

    public function setVersion( $data): void
    {$this->version = $data;}


    #[ORM\Column(type: 'text',options:["default" => "1.1.1"], nullable:true)]
    private string $chance;

    public function getChance()
    {return $this->chance;}

    public function setChance( $data): void
    {$this->chance = $data;}


 
    #[ORM\ManyToOne(targetEntity: store::class, inversedBy:"store")]
    #[ORM\JoinColumn(name: 'store_id', referencedColumnName: 'id')]
    private store|null $store_id = null;


    public function getStore()
    {
        return $this->store_id;
    }

    public function setStore( $data): void
    {
      $this->store_id=$data;
    }



    #[ORM\ManyToOne(targetEntity: form_type::class, inversedBy:"form_type")]
    #[ORM\JoinColumn(name: 'type_id', referencedColumnName: 'id')]
    private formtype|null $type_id = null;


    public function getFormType()
    {
        return $this->type_id;
    }

    public function setFormType( $data): void
    {
      $this->type_id=$data;
    }

    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $priority = null;

    public function getPriority(){
        return $this->priority;

    }
    
    public function setPriority ( $data):void
    {
        $this->priority = $data;
    }
    
    #[ORM\Column(type: 'text',nullable:true)]
    private string $remark;

    public function getRemark()
    {return $this->remark;}

    public function setRemark( $data): void
    {$this->remark = $data;}

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $open;

    public function setOpendate( $data): void
    {
        $this->open=$data;
    }
    public function getOpendate()
    {
        return $this->open;
    }

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $close;

    public function setClosedate( $data): void
    {
        $this->close=$data;
    }
    public function getClosedate()
    {
        return $this->close;
    }
    

    #[ORM\ManyToOne(targetEntity: form::class, inversedBy:"form")]
    #[ORM\JoinColumn(name: 'parentform_id', referencedColumnName: 'id')]
    private form|null $parentform_id = null;

    public function getParentform()
     {
     return $this->parentform_id;
     }

    public function setParentform( $data): void
    {
      $this->parentform_id=$data;
    }

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $date_created;

    public function setDatecreated( $data): void
    {
        $this->date_created=$data;
    }
    public function getDatecreated()
    {
        return $this->date_created;
    }

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $date_effective;

    public function setDateeffective( $data): void
    {
        $this->date_effective=$data;
    }
    public function getDateeffective()
    {
        return $this->date_effective;
    }

    #[ORM\ManyToOne(targetEntity: user::class, inversedBy:"user")]
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'id')]
    private user|null $created_by = null;

    public function getCreatedby()
    {
        return $this->created_by;
    }

    public function setCreatedby( $data): void
    {
      $this->created_by=$data;
    }
        
    #[ORM\JoinTable(name: 'form_task')]
    #[ORM\JoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'task_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: task::class)]
    private Collection $form_task;

    public function getTaskfield()
    {
        return $this->form_task;
    }
    public function setTaskfield( $data): void
    {
        $this->form_task->add($data);
    }  
    
    #[ORM\JoinTable(name: 'form_form')]
    #[ORM\JoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'form_related_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: form::class)]
    private Collection $form_link;

    public function getFormlink()
    {
        return $this->form_link;
    }
    public function setFormlink( $data): void
    {
        $this->form_link->add($data);
    }    


    #[ORM\JoinTable(name: 'form_connection_form')]
    #[ORM\JoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'connection_form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: connection_form::class)]
    private Collection $connection_form;

    public function getConnectionform()
    {
        return $this->connection_form;
    }
    public function setConnectionform( $data): void
    {
        $this->connection_form->add($data);
    }   


    #[ORM\JoinTable(name: 'form_justification_form')]
    #[ORM\JoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'justification_form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: justification_form::class)]
    private Collection $justification_form;

    public function getJustificationform()
    {
        return $this->justification_form;
    }
    public function setJustificationform( $data): void
    {
        $this->justification_form->add($data);
    }   

    public function __construct()
    {
        $this->form_task = new ArrayCollection();
        $this->form_link = new ArrayCollection();
        $this->connection_form = new ArrayCollection();
        $this->justification_form = new ArrayCollection();
    }




}
