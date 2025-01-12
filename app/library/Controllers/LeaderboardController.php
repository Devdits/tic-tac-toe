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
        $playersTable = new PlayersTable();
        $view->players = $playersTable->getLeaders();
        $numberOfPlayers = $playersTable->getCount();
        $view->numberOfPlayers = $numberOfPlayers;
        $view->showNumberOfPlayers =
            (PlayersTable::NUMBER_OF_PLAYERS_SHOWN > $numberOfPlayers)
                ? $numberOfPlayers
                : PlayersTable::NUMBER_OF_PLAYERS_SHOWN;
        return $view;
    }
}
