<?php

namespace Iutncy\Sae\Tag;

class Tag{
    private $libelle;
    private $description;

    public function __construct($libelle, $description){
        $this->libelle = $libelle;
        $this->description = $description;
    }
}