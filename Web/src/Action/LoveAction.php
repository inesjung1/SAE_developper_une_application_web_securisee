<?php

namespace Iutncy\Sae\Action;

class LoveAction
{
    public function __construct()
    {
    }

    public function execute(): string{
        $this->ajouterLikeTouite();
        header('Location: '.$_SERVER['PHP_SELF'].'?action=DefaultAction');
        return '';
    }

    public function ajouterLikeTouite(){
        echo "reroijgogjnerjn";
        $html = <<<HTML
        <script>
        function incremente() {
            var l = document.getElementById('love');
            echo l;
        }
        </script>
        HTML;
        echo $html;
        return $html;
    }




}