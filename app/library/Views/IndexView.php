<?php

namespace App\Views;

class IndexView extends AbstractView
{
    public string $playerName;
    public int $gridSize;

    public function __construct()
    {
        $this->setTitle("Hello player!");
    }

    public function render(): void
    {
        include __DIR__ . '/index.php';
    }
}
