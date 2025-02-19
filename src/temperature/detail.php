<?php
namespace TemperatureDb\Process;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;



#[ORM\Entity]
#[ORM\Table(name: 'detail')]
class detail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

    public function getId():int
    {

    return $this->id;
    }   


    #[ORM\Column(type: 'text',nullable:true)]
    private string $value;

    public function setValue(string $data): void
    {      
        $this->value = $data;
    }
	
    
    public function getValue():string
    {
        return $this->value;
    }

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $datetime;

    public function setDatetime(DateTime $datetime): void
    {      
        $this->datetime=$datetime;
    }
    public function getDateTime(): DateTime
    {

    return $this->datetime;
    }


    #[ORM\ManyToOne(targetEntity: type::class, inversedBy:"detail")]
    #[ORM\JoinColumn(name: 'type_id', referencedColumnName: 'id')]
    private type|null $type = null;

    public function setType(type $data): void
    {      
        $this->type = $data;
    }

    public function getType():type
    {
    return $this->type;
    }

   

}
