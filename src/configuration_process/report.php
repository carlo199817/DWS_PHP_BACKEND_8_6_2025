<?php
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;



#[ORM\Entity]
#[ORM\Table(name: 'report')]
class report
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


    #[ORM\ManyToOne(targetEntity: form::class, inversedBy:"form")]
    #[ORM\JoinColumn(name: 'form_id', referencedColumnName: 'id')]
    private form|null $form_id = null;


    public function getForm()
    {
        return $this->form_id;
    }

    public function setForm(form $data): void
    {
      $this->form_id=$data;
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

    
    #[ORM\ManyToOne(targetEntity: report_type::class, inversedBy:"report_type")]
    #[ORM\JoinColumn(name: 'type_id', referencedColumnName: 'id')]
    private report_type|null $type_id = null;


    public function getReporttype()
    {
        return $this->type_id;
    }

    public function setReporttype(report_type $data): void
    {
      $this->type_id=$data;
    }



    #[ORM\JoinTable(name: 'report_plot')]
    #[ORM\JoinColumn(name: 'report_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'plot_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: plot::class)]
    private Collection $report_plot;

    public function getReportplot(): Collection
    {
        return $this->report_plot;
    }
    public function setReportplot(plot $data): void
    {
        $this->report_plot->add($data);
    }    

    public function __construct()
    {
        $this->report_plot = new ArrayCollection();
    }






}