<?php
namespace process;
use configuration_process\form;
use configuration_process\itinerary;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;





#[ORM\Entity]
#[ORM\Table(name: 'user')]
class user
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    public function getId()
    {
        return $this->id;
    }

    public function setId( $data): void
    {
        $this->id= $data;
    }

    #[ORM\JoinTable(name: 'user_form_task')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: form::class)]
    private Collection $user_form_task;

    public function getUserformtask()
    {
        return $this->user_form_task;
    }
    public function setUserformtask($data): void
    {
        $this->user_form_task->add($data);
    }

    #[ORM\JoinTable(name: 'user_form_connection')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: form::class)]
    private Collection $user_form_connection;

    public function getUserformconnection()
    {
        return $this->user_form_connection;
    }
    public function setUserformconnection( $data): void
    {
        $this->user_form_connection->add($data);
    }


    public function removeUserformconnection($links,$data)
    {
        foreach ($links as $link) {
             if ($this->user_form_connection->contains($data)) {
                 $this->user_form_connection->removeElement($data);
             }
        }
       return $links;
    }


    #[ORM\JoinTable(name: 'user_form_generator')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'form_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: form::class)]
    private Collection $user_form_generator;

    public function getUserformgenerator()
    {
        return $this->user_form_generator;
    }
    public function setUserformgenerator( $data): void
    {
        $this->user_form_generator->add($data);
    }

    #[ORM\JoinTable(name: 'user_itinerary_connection')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'itinerary_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: itinerary::class)]
    private Collection $user_itinerary_connection;

    public function getUseritineraryconnection()
    {
        return $this->user_itinerary_connection;
    }
    public function setUseritineraryconnection( $data): void
    {
        $this->user_itinerary_connection->add($data);
    }

    public function __construct()
    {
        $this->user_form_task = new ArrayCollection();
        $this->user_form_generator = new ArrayCollection();
        $this->user_form_connection = new ArrayCollection();
    }


}


