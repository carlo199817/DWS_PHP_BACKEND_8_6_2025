<?php
namespace configuration_process;
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
    private string $formula;

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

    
    #[ORM\ManyToOne(targetEntity: field_type::class, inversedBy:"field_type")]
    #[ORM\JoinColumn(name: 'type_id', referencedColumnName: 'id')]
    private field_type|null $type_id = null;

    
    public function getFieldtype()
    {
        return $this->type_id;
    }

    public function setFieldtype( $data): void
    {
      $this->type_id=$data;
    }







}
