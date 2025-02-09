<?php

namespace App\Controllers;

use App\Models\PlayersTable;
use App\Views\AbstractView;
use App\Views\LeaderboardView;

class LeaderboardController implements ControllerInterface
{
    public function indexAction(): AbstractView
    {
        $view = new LeaderboardView();

        // Todo: redo this crap!
        $players = (new PlayersTable())->getTotalLeaders();
        $playerCount = count((new PlayersTable())->getAllUniquePlayers());
        $view->players = $players;
        $view->playerCount = $playerCount;

        return $view;
    }

}
