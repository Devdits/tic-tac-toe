<?php

namespace App\Views;

class IndexView extends AbstractView
{
    public int    $gridSize;
    public string $playerName;

    public function __construct()
    {
        $this->playerName = ($_GET['player_name']) ?? "Player Name";
        $this->setTitle( "Hello " . $this->playerName . "!");
    }

    public function render(): void
    {
        include __DIR__ . '/index.phtml';
    }
}
