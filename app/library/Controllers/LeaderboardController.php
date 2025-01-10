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

        $players = (new PlayersTable())->getLeaders();
        $view->players = $players;

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
