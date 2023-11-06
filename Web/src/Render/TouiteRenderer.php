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
        switch ($selector) {
            case self::LONG:
                $html = <<<HTML
                <div class="touite">
                    <div class="touite-header">
                        <div class="touite-pseudo">$pseudo</div>
                        <div class="touite-email">$email</div>
                        <div class="touite-date">$date</div>
                    </div>
                    <div class="touite-content">$content</div>
                </div>
                HTML;
                break;
            case self::COMPACT:
                $html = <<<HTML
                <div class="touite">
                    <div class="touite-content">$content</div>
                </div>
                HTML;
        }
        return $html;
    }
}