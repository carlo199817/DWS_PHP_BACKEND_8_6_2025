<?php
namespace configuration;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'category')]
class category
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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($data): void
    {      
        $this->description= $data;
    }

    #[ORM\JoinTable(name: 'category_user')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: user::class)]
    private Collection $category_user;

    public function getCategoryuser()
    {
        return $this->category_user;
    }
    public function setCategoryuser($data): void
    {
        $this->category_user->add($data);
    }


 public function removeCategoryuser($users,$data)
    {
        foreach ($users as $user) {
             if ($this->category_user->contains($data)) {
                 $this->category_user->removeElement($data);
             }
        }
       return $users;
    }


    public function __construct()
    {
        $this->category_user = new ArrayCollection();
    }



}
