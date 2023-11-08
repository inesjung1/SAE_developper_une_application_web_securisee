<?php

namespace Iutncy\Sae\Action;

class DeconnexionAction extends Action
{
    public function execute(): string
    {
        setcookie('user', "0", time() + 3600, '/');
        header('Location: '.$_SERVER['PHP_SELF'].'?action=DefaultAction');
        return '';
    }

}