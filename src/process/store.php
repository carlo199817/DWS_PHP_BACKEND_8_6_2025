<?php
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;



#[ORM\Entity]
#[ORM\Table(name: 'store')]
class second_store
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $data): void
    {      
        $this->id= $data;
    }

    #[ORM\JoinTable(name: 'store_form')]
    #[ORM\JoinColumn(name: 'store_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: form::class)]
    private Collection $store_form;

    public function getStorename(): Collection
    {
        return $this->store_form;
    }
    public function setStorename(form $data): void
    {
        $this->store_form->add($data);
    }

    

    public function __construct()
    {
        $this->store_form = new ArrayCollection();

    }


}

