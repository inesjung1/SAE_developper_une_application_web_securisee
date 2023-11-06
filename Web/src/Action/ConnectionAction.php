<?php
declare(strict_types=1);
namespace Iutncy\Sae\Action;
class ConnectionAction extends Action {
public function __construct() {}
    public function execute(): string {
        $html = <<<HTML
            <form action="index.php?action=connection" method="post">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
                <input type="submit" value="Connection">
            </form>
        HTML;
        return $html;
    }
}