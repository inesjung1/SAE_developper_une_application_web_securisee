<?php

namespace Iutncy\Sae\Render;

use Iutncy\Sae\Touites\ListTouite;

class ListTouiteRenderer implements Renderer
{

    private ListTouite $listTouite;

    public function __construct(ListTouite $li)
    {
        $this->liTouite = $li;
    }

    public function render(int $selector): string
    {
        //affichage de la liste des touites par odre chronologique
        foreach ($this->liTouite as $touite) {
            $touiteRenderer = new TouiteRenderer($touite);
            $html .= $touiteRenderer->render($selector);
        }
        return $html;
    }

}