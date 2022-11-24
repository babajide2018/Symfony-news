<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;


    //migrations
    #[ORM\Column(length: 50)]
    private string $title;

    #[ORM\Column(length: 255)]
    private string $body;

//    public function getId(): ?int
//    {
//        return $this->id;
//    }


    //Getters & Settlers

    public function getId(){
        return $this->id;
    }


    public function getTitle(){
        return $this->title;
    }

    public function setTitle($title){
        return $this->title = $title;
    }


    public function getBody(){
        return $this->body;
    }


    public function setBody($body){
        return $this->body = $body;
    }



}
