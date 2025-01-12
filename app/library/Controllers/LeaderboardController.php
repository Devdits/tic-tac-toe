<?php

namespace App\Controllers;

use App\Models\PlayersTable;
use App\Views\AbstractView;
use App\Views\LeaderboardView;

class LeaderboardController implements ControllerInterface
{
    public function indexAction(): AbstractView
    {
        $totalPlayers = (new PlayersTable())->getCount();
        $leaders = (new PlayersTable())->getLeaders(); 

        $view = new LeaderboardView();
        $view->totalPlayers = $totalPlayers;
        $view->leaders = $leaders;

        return $view;
    }

}
