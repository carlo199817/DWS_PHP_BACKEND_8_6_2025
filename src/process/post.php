<?php
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity]
#[ORM\Table(name: 'post')]
class post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int|null $id = null;

   
    public function getId(): int
    {
        return $this->id;
    }
    
    

    #[ORM\Column(type:"datetime", options:["default" => "CURRENT_TIMESTAMP"],nullable:true)]
    private $date_created;

    public function setDatecreated(DateTime $data): void
    {
        $this->date_created=$data;
    }
    public function getDatecreated():DateTime
    {
        return $this->date_created;
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



    
    #[ORM\JoinTable(name: 'post_react_post')]
    #[ORM\JoinColumn(name: 'post_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'react_post_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: react_post::class)]
    private Collection $react_post;

    public function getReactpost(): Collection
    {
        return $this->react_post;
    }
    public function setReactpost(react_post $data): void
    {
        $this->react_post->add($data);
    }  
    
    
        
    #[ORM\JoinTable(name: 'post_content')]
    #[ORM\JoinColumn(name: 'post_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'content_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: content::class)]
    private Collection $post_content;

    public function getPostcontent(): Collection
    {
        return $this->post_content;
    }
    public function setPostcontent(content $data): void
    {
        $this->post_content->add($data);
    }   


    #[ORM\JoinTable(name: 'post_comment_post')]
    #[ORM\JoinColumn(name: 'post_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'comment_post_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: comment_post::class)]
    private Collection $comment_post;

    public function getCommentpost(): Collection
    {
        return $this->comment_post;
    }
    public function setCommentpost(comment_post $data): void
    {
        $this->comment_post->add($data);
    }   



    public function __construct()
    {
        $this->react_post = new ArrayCollection();
        $this->post_content = new ArrayCollection();
        $this->comment_post = new ArrayCollection();
    }




}