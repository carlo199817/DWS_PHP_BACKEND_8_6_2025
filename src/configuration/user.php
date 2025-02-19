<?php
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use ReallySimpleJWT\Token;
use ReallySimpleJWT\Jwt;
use ReallySimpleJWT\Validate;
use ReallySimpleJwt\Decode;
use ReallySimpleJwt\Helper\Validator;

use ReallySimpleJWT\Exception\ValidateException;
use ReallySimpleJWT\Exception\TokenException;



class tokens {
    private int $id;
    private const SECRET_KEY = 'secyew44wfdfd23wsdsdsdzsad!ReT423*&';

    public function getToken(int $id)
    {
        return Token::create($id, self::SECRET_KEY, time() + 2000000, "*", ['alg' => 'HS256']);
    }

    public function getValidation(string $myToken)
    { 
        try {
            return Token::validate($myToken, self::SECRET_KEY);
        } catch (ParsedException $e) {
            return false;  
        }
    }

    public function decodeToken(string $token): ?array
    {

        $parts = explode('.', $token);


        if (count($parts) !== 3) {
            return null;
        }


        $payload = $this->base64UrlDecode($parts[1]);


        $decodedPayload = json_decode($payload, true);


        if ($decodedPayload && isset($decodedPayload['user_id'])) {
            return $decodedPayload;
        }

        return null;
    }


    private function base64UrlDecode(string $data): string
    {

        $data = str_replace(['-', '_'], ['+', '/'], $data);


        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= str_repeat('=', 4 - $mod4);
        }

        return base64_decode($data);
    }
}



#[ORM\Entity]
#[ORM\Table(name: 'user')]
class user
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
    private string $username;

    public function getUsername(): string
    {   
        return $this->username; 
    }

    public function setUsername(string $data): void
    {      
        $this->username = $data;
    }

 

    #[ORM\Column(type: 'string',nullable:true)]
    private string $password;

    public function setPassword($data): void
    {
      $this->password = password_hash($data,PASSWORD_DEFAULT);
    }    
    public function authenticateUser($data): bool
    {
        if (password_verify($data, $this->password)) {
            return true;
        }
        return false;
    }


    #[ORM\Column(type: 'string',nullable:true)]
    private $first_name;

    public function getFirstname(): string
    {   
        if(json_encode($this->first_name)!="null"){
        return $this->first_name;}  else{
            }
    }

    public function setFirstname(string $data): void
    {      
        $this->first_name = $data;
    }



    #[ORM\Column(type: 'string',nullable:true)]
    private $middle_name;

    public function getMiddlename(): string
    {   
        return $this->middle_name; 
    }

    public function setMiddlename(string $data): void
    {      
        $this->middle_name = $data;
    }

    
    #[ORM\Column(type: 'string',nullable:true)]
    private $last_name;

    public function getLastname(): string
    {   
        if(json_encode($this->last_name)!="null"){
                return $this->last_name;}
        else{
            }
    }

    public function setLastname(string $data): void
    {      
        $this->last_name = $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $suffix;

    public function getSuffix(): string
    {
        return $this->suffix;
    }

    public function setSuffix(string $data): void
    {      
        $this->suffix = $data;
    }


    #[ORM\Column(type: 'string',nullable:true)]
    private $email;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $data): void
    {      
        $this->email= $data;
    }


    #[ORM\Column(type: 'string',nullable:true, options:["default" => "0"])]
    private $employee_number;

    public function getEmployeenumber()
    {   
        return $this->employee_number; 
    }

    public function setEmployeenumber(string $data): void
    {      
        $this->employee_number = $data;
    }

    #[ORM\Column(type: 'string',nullable:true)]
    private $location;


    public function getLocation(): string
    {   
        return $this->location; 
    }

    public function setLocation(string $data):void
    {
        $this->location = $data;
    }

    #[ORM\Column(type:"datetime",nullable:true)]
    private $time_location;

    public function getTimelocation():DateTime
    {
        return $this->time_location;

    }

    public function setTimelocation(DateTime $data):void
    {
        $this->time_location = $data;
    }


    #[ORM\ManyToOne(targetEntity: store::class, inversedBy:"store")]
    #[ORM\JoinColumn(name: 'store_id', referencedColumnName: 'id')]
    private store|null $store_id = null;


    public function getStore()
    {
        return $this->store_id;
    }

    public function setStore(store $data): void
    {
      $this->store_id=$data;
    }

    #[ORM\ManyToOne(targetEntity: user_type::class, inversedBy:"user_type")]
    #[ORM\JoinColumn(name: 'type_id', referencedColumnName: 'id')]
    private user_type|null $type_id = null;

    
    public function getUsertype()
    {
        return $this->type_id;
    }

    public function setUsertype(user_type $data): void
    {
      $this->type_id=$data;
    }

    #[ORM\Column(type: 'boolean', nullable:true)]
    private $disable;

    public function getDisable(): bool
    {
        return $this->disable;
    }

    public function setDisable(bool $data): void
    {      
        $this->disable=$data;
    }


    #[ORM\Column(type: 'decimal', nullable:true)]
    private ?string $distance;

    public function getDistance(): ?string
    {
        return $this->distance;
    }

    public function setDistance(?string $data): void
    {      
        $this->distance=$data;
    }


    #[ORM\Column(type: 'string',options:["default" => "profile.png"])]
    private string $picture;

    public function getPicture(): string
    {   
        return $this->picture; 
    }

    public function setPicture(string $picture): void
    {      
        $this->picture = $picture;
    }


    #[ORM\JoinTable(name: 'user_store')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'store_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: store::class)]
    private Collection $user_store;

    public function getUserstore(): Collection
    {
        return $this->user_store;
    }
    public function setUserstore(store $data): void
    {
        $this->user_store->add($data);
    }


    public function __construct()
    {
        $this->user_store = new ArrayCollection();
    }


}