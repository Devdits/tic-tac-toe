<?php

namespace App\Views;

class LeaderboardView extends AbstractView
{
    /** @var array[][] */
    public array $players;

    public int $numberOfPlayers;

    public int $showNumberOfPlayers;

    public bool $playerWins;

    public function __construct()
    {
        $this->setTitle("Leaderboard");
    }

    public function render(): void
    {
        include __DIR__ . '/leaderboard.php';
    }
}
