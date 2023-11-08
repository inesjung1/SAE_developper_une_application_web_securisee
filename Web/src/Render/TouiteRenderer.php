<?php
declare(strict_types=1);
namespace Iutncy\Sae\Render;
use Iutncy\Sae\Touites\Touite;
class TouiteRenderer implements Renderer{
    private Touite $touite;
    private int $id;
    public function __construct(Touite $touite, int $id) {
        $this->touite = $touite;
        $this->id = $id;
    }
    public function render(int $selector=self::COMPACT): string {
        $touite = $this->touite;
        $user = $touite->getAuteur();
        $pseudo = $user->getPseudo();
        $email = $user->getEmail();
        $content = $touite->getTexte();
        $date = $touite->getDate();
        $love = $touite->getLove();
        $id = $this->id;
        $dislove = $touite->getDislove();
        switch ($selector) {
            case self::LONG:
                $html = <<<HTML
                <div class="touite">
                    <div class="touite-header">
                        <div class="touite-pseudo">
                HTML;
                $html.= '<a class="user" href="index.php?action=UtilisateurAction&user='.$id.'">' . $pseudo . '</a>';
                $html .= <<<HTML
                        </div>
                        <div class="touite-email">$email</div>
                        <div class="touite-date">$date</div>
                    </div>
                    <div class="touite-content">$content</div>
                    <button id="love" onclick="window.location.href='index.php?action=loveaction'">Love : $love</button>
                    <div class="touite-like">like : $love</div>
                    <div class="touite-dislike">Dislike : $dislove</div>
                    </div> <br>
                HTML;
                break;
            case self::COMPACT:
                $html = <<<HTML
                <div class="touite">
                    <div class="touite-content">$content</div>
                    <button id="love" onclick="window.location.href='index.php?action=loveaction'">Love : $love</button>
                    <div class="touite-like">like : $love</div>
                    <div class="touite-dislike">Dislike : $dislove</div>
                </div> <br>
                HTML;
        }
        return $html;
    }
}