<?php

namespace Iutncy\Sae\Tag;
use Iutncy\Sae\Exception\InvalideName;

class Tag{
    private $libelle;
    private $description;

    public function __construct($libelle, $description){
        $this->libelle = $libelle;
        $this->description = $description;
    }

    public function __get(string $at): mixed
    {
        if (!property_exists($this, $at)) {
            throw new InvalideName(get_called_class() . "invalid property : $at");
        }
        return $this->$at;
    }
}