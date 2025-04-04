<?php
namespace configuration_process;
use configuration\path;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'field')]
class field
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
    private $question;

    public function getQuestion()
    {
        return $this->question;
    }

    public function setQuestion( $data): void
    {      
        $this->question= $data;
    }


    
    #[ORM\Column(type: 'string',nullable:true)]
    private $answer;

    public function getAnswer()
    {
        return $this->answer;
    }

    public function setAnswer( $data): void
    {      
        $this->answer= $data;
    }

 


    #[ORM\Column(type: 'text',nullable:true)]
    private  $formula;

    public function getFormula()
    {
        return $this->formula;
    }

    public function setFormula( $data): void
    {      
        $this->formula= $data;
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

    
   
    #[ORM\Column(type: 'integer',nullable:true)]
    private int|null $type_id = null;

    public function getFieldtype()
    {
        return $this->type_id;
    }

    public function setFieldtype( $data): void
    {      
        $this->type_id= $data;
    }




    
    #[ORM\JoinTable(name: 'field_field')]
    #[ORM\JoinColumn(name: 'field_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'field_related_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: field::class)]
    private Collection $field_link;

    public function getFieldlink()
    {
        return $this->field_link;
    }
    public function setFieldlink($data): void
    {
        $this->field_link->add($data);
    }

   
    public function removeFieldlink($links,$data)
    {
        foreach ($links as $link) {
             if ($this->field_link->contains($data)) {
                 $this->field_link->removeElement($data);
             }
        }
       return $links;
    }  

    public function setAllfieldlink(Collection $data): void
    {
        $this->field_link = $data;
    } 


        
    #[ORM\ManyToMany(targetEntity: task::class, mappedBy: 'task_field')]
    private Collection $task_field;



    public function getTaskfield(): Collection
    {
        return $this->task_field;
    }


    #[ORM\ManyToMany(targetEntity: field::class, mappedBy: 'field_link')]
    private Collection $bidirectional;

    public function getBidirectional(): Collection
    {
        return $this->bidirectional;
    }




    public function __construct()
    {
        $this->field_link = new ArrayCollection();
        $this->task_field = new ArrayCollection();
    	$this->bidirectional = new ArrayCollection();
    }
  
  

}
