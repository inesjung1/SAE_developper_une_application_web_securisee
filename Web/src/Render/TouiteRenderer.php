<?php
declare(strict_types=1);
namespace Iutncy\Sae\Render;
use Iutncy\Sae\Touites\Touite;
class TouiteRenderer implements Renderer{
    private Touite $touite;
    public function __construct(Touite $touite) {
        $this->touite = $touite;
    }
    public function render(int $selector=self::COMPACT): string {
        $touite = $this->touite;
        $user = $touite->getAuteur();
        $pseudo = $user->getPseudo();
        $email = $user->getEmail();
        $content = $touite->getTexte();
        $date = $touite->getDate();
        $love = $touite->getLove();
        $dislove = $touite->getDislove();
        switch ($selector) {
            case self::LONG:
                $html = <<<HTML
                <div class="touite">
                    <div class="touite-header">
                        <div class="touite-pseudo"><a class="user" href="index.php?action=ShowProfil">$pseudo</div></a>
                        <div class="touite-email">$email</div>
                        <div class="touite-date">$date</div>
                    </div>
                    <div class="touite-content">$content</div>
                    <div class="touite-like">like : $love</div>
                    <div class="touite-dislike">Dislike : $dislove</div>
                </div> <br>
                HTML;
                break;
            case self::COMPACT:
                $html = <<<HTML
                <div class="touite">
                    <div class="touite-content">$content</div>
                    <div class="touite-like">like : $love</div>
                    <div class="touite-dislike">Dislike : $dislove</div>
                </div> <br>
                HTML;
        }
        return $html;
    }
}