<?php

namespace App\Controllers;

use App\Views\JsonView;
use App\Views\AbstractView;
use App\Models\PlayersTable;
use App\Views\LeaderboardView;

class LeaderboardController extends Controller implements ControllerInterface
{
    public function indexAction(): AbstractView
    {
        $view = new LeaderboardView();

        $players_table = new PlayersTable();
        $players = $players_table->getLeaders();
        $view->players = $players;
        $view->total_players = $players_table->getCount();

        return $view;
    }

    public function updateAction(): AbstractView
    {
        $request = $this->getJsonRequest();

        $players = new PlayersTable();

        $players->addRow(
            $request['name'],
            $request['gridSize'],
            $request['duration']
        );

        $view = new JsonView();
        $view->data = [
            'updated' => true,
        ];

        return $view;
    }

}
