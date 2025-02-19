<?php
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use ClientDb\Process\form;
use ClientDb\Process\itinerary;
use MainDB\Configuration\user;


#[ORM\Entity]
#[ORM\Table(name: 'automation_form')]
class automation_form
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId(): int
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

    public function setForm(form $data): void
    {
      $this->form_id = $data;
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

    
    #[ORM\ManyToOne(targetEntity: itinerary::class, inversedBy:"itinerary")]
    #[ORM\JoinColumn(name: 'itinerary_id', referencedColumnName: 'id')]
    private itinerary|null $itinerary_id = null;

    public function getItinerary()
    {
        return $this->itinerary_id;
    }

    public function setItinerary(itinerary $data): void
    {
      $this->itinerary_id = $data;
    }


         






}