<?php
namespace configuration_process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;



#[ORM\Entity]
#[ORM\Table(name: 'part')]
class part
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId()
    {
        return $this->id;
    }



    #[ORM\ManyToMany(targetEntity: equipment::class, mappedBy: 'equipment_part')]
    private Collection $bidirectional;

    public function getBidirectional(): Collection
    {
        return $this->bidirectional;
    }




    #[ORM\Column(type: 'text',nullable:true)]
    private $description;

    public function getDescription()
    {return $this->description;}

    public function setDescription( $data): void
    {$this->description = $data;}


    #[ORM\Column(type: 'text',nullable:true)]
    private $question;

    public function getQuestion()
    {return $this->question;}

    public function setQuestion( $data): void
    {$this->question = $data;}


    #[ORM\Column(type: 'text',nullable:true)]
    private $answer;

    public function getAnswer()
    {return $this->answer;}

    public function setAnswer( $data): void
    {$this->answer = $data;}


    #[ORM\Column(type: 'boolean', nullable: true)]
    private $remove;

    public function getRemove()
    {
        return $this->remove;
    }

    public function setRemove($data): void
    {
        $this->remove = $data;
    }




        public function __construct()
    {
        $this->bidirectional = new ArrayCollection();
    }




   
}
