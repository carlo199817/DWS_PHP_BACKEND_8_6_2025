<?php
namespace configuration;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'table_category')]
class table_category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;
    
    public function getId()
    {
        return $this->id;
    }

    #[ORM\ManyToOne(targetEntity: category::class, inversedBy:"category")]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id')]
    private category|null $category_id = null;
    public function getCategory()
    {
        return $this->category_id;
    }
    public function setCategory( $data): void
    {
        $this->category_id=$data;
    }

}
