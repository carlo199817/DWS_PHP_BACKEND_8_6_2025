<?php


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ClientDb\Process\form;
use ClientDb\Process\form_type;
use MainDB\Configuration\user;


#[ORM\Entity]
#[ORM\Table(name: 'automation_form_publishing')]
class automation_form_publishing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId()
    {
        return $this->id;
    }
    
    #[ORM\ManyToOne(targetEntity: form::class, inversedBy:"form")]
    #[ORM\JoinColumn(name: 'form_id', referencedColumnName: 'id')]
    private form|null $form_id = null;

    public function getForm()
    {
        return $this->form_id;
    }

    public function setForm($data): void
    {
      $this->form_id = $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $remark;

    public function getRemark()
    {
        return $this->remark;
    }

    public function setRemark($data): void
    {      
        $this->remark= $data;
    }

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $date_publish;

    public function setDatepublish($data): void
    {
        $this->date_publish=$data;
    }
    public function getDatepublish()    
    {
        return $this->date_publish;
    }

    #[ORM\ManyToOne(targetEntity: user::class, inversedBy:"user")]
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'id')]
    private user|null $created_by = null;

    public function getCreatedby()
    {
        return $this->created_by;
    }

    public function setCreatedby($data): void
    {
      $this->created_by=$data;
    }

    #[ORM\ManyToOne(targetEntity: form_type::class, inversedBy:"form_type")]
    #[ORM\JoinColumn(name: 'form_type_id', referencedColumnName: 'id')]
    private form_type|null $form_type_id = null;

    public function getFormtype()
    {
        return $this->form_type_id;
    }

    public function setFormtype($data): void
    {
        $this->form_type_id = $data;
    }

    #[ORM\Column(type: 'integer',nullable:true)]
    private int $priority;

    public function getPriority()
    {
        return $this->priority;
    }

    public function setPriority($data): void
    {      
        $this->priority= $data;
    }
}