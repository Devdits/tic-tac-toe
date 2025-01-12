<?php

namespace App\Views;

class LeaderboardView extends AbstractView
{
    /** @var int */
    public int $totalPlayers;

    /** @var array[][] */
    public array $leaders;

    public function __construct()
    {
        $this->setTitle("Leaderboard");
    }

    public function render(): void
    {
        include __DIR__ . '/leaderboard.php';
    }
}
