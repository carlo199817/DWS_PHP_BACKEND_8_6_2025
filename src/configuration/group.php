<?php
namespace MainDb\Configuration;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'group')]
class group
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

    #[ORM\ManyToOne(targetEntity: group_type::class, inversedBy:"group_type")]
    #[ORM\JoinColumn(name: 'type_id', referencedColumnName: 'id')]
    private group_type|null $type_id = null;

    public function getGrouptype()
    {
        return $this->type_id;
    }

    public function setGrouptype(group_type $data): void
    {
      $this->type_id=$data;
    }

    #[ORM\JoinTable(name: 'group_user')]
    #[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: user::class)]
    private Collection $group_user;

    public function getGroupuser(): Collection
    {
        return $this->group_user;
    }
    public function setGroupuser(user $data): void
    {
        $this->group_user->add($data);
    }
 
    #[ORM\JoinTable(name: 'group_store')]
    #[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'store_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: store::class)]
    private Collection $group_store;

    public function getGroupstore(): Collection
    {
        return $this->group_store;
    }
    public function setGroupstore(store $data): void
    {
        $this->group_store->add($data);
    }
    
    #[ORM\JoinTable(name: 'group_group')]
    #[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'group_related_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: group::class)]
    private Collection $group_link;

    public function getGrouplink(): Collection
    {
        return $this->group_link;
    }
    public function setGrouplink(group $data): void
    {
        $this->group_link->add($data);
    }

    public function __construct()
    {
        $this->group_user = new ArrayCollection();
        $this->group_store = new ArrayCollection();
        $this->group_link = new ArrayCollection();
    }



}