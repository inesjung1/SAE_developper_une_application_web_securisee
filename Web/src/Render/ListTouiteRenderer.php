<?php

namespace Iutncy\Sae\Render;

use Iutncy\Sae\Touites\ListTouite;

class ListTouiteRenderer implements Renderer
{

    private ListTouite $listTouite;

    public function __construct(ListTouite $li)
    {
        $this->listTouite = $li;
    }

    public function render(int $selector=self::COMPACT): string
    {
        $html = "<h1> Liste des touites </h1>";
        //affichage de la liste des touites par odre chronologique
        foreach ($this->listTouite as $touite) {
            $touiteRenderer = new TouiteRenderer($touite);
            $html .= $touiteRenderer->render($selector);
        }
        return $html;
    }

}