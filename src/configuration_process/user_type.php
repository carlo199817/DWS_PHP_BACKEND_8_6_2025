<?php
namespace configuration_process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'user_type')]
class user_type
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
    private $description;

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription( $data): void
    {
        $this->description= $data;
    }



    #[ORM\JoinTable(name: 'user_type_platform')]
    #[ORM\JoinColumn(name: 'user_type_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'platform_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: platform::class)]
    private Collection $user_type_platform;

    public function getUsertypeplatform()
    {
        return $this->user_type_platform;
    }
    public function setUsertypeplatform( $data): void
    {
        $this->user_type_platform->add($data);
    }

       
    #[ORM\JoinTable(name: 'user_type_form')]
    #[ORM\JoinColumn(name: 'user_type_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: form::class)]
    private Collection $user_type_form;

    public function getUsertypeform()
    {
        return $this->user_type_form;
    }
    public function setUsertypeform( $data): void
    {
        $this->user_type_form->add($data);
    }



    public function __construct()
    {
        $this->user_type_platform = new ArrayCollection();
        $this->user_type_form = new ArrayCollection();
    }




}
