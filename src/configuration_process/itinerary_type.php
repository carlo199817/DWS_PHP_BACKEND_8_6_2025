<?php
namespace ClientDb\Process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'itinerary_type')]
class itinerary_type
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

    
    #[ORM\JoinTable(name: 'itinerary_type_form')]
    #[ORM\JoinColumn(name: 'itinerary_type_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: form::class)]
    private Collection $itinerary_type_form;

    public function getItinerarytypeform(): Collection
    {
        return $this->itinerary_type_form;
    }

    public function setItinerarytypeform(form $data): void
    {
        $this->itinerary_type_form->add($data);
    }

    public function __construct()
    {
        $this->itinerary_type_form = new ArrayCollection();
    }




}