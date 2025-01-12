<?php

namespace App\Controllers;

use App\Models\PlayersTable;
use App\Views\AbstractView;
use App\Views\JsonView;
use App\Views\LeaderboardView;

session_start();

class LeaderboardController implements ControllerInterface
{
    const INPUT_VALIDATION_MESSAGE = 'Input must be between 3 and 20 characters and contain only alphanumeric characters.';
    const MISSING_DATA_MESSAGE = 'Missing data.';
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
        $view->playerWins = false;
        if (isset($_SESSION['player_win']) && $_SESSION['start']) {
            $view->playerWins = $_SESSION['player_win'];
            if (!isset($_SESSION['player_time_seconds'])) {
                $_SESSION['play_time_seconds'] = time() - $_SESSION['start'];
            }
        }

        return $view;
    }

    public function registerResultsAction(): AbstractView
    {
        $view = new JsonView();
        $message = 'ok';
        $requestJson = file_get_contents('php://input');
        $request = json_decode($requestJson, true);
        $playerName = $request['player_name'] ?? null;
        if (!preg_match('/^[a-zA-Z0-9]{3,20}$/', $playerName)) {
            $view->setHttpResponseCode(400);
            $message = self::INPUT_VALIDATION_MESSAGE;
        } else if (isset($_SESSION['grid_size']) && isset($_SESSION['play_time_seconds'])) {
            $gridSize = $_SESSION['grid_size'];
            $playTimeSeconds = $_SESSION['play_time_seconds'];
            $playersTable = new PlayersTable();
            $playersTable->addRow($playerName, $gridSize, $playTimeSeconds, date('Y-m-d H:i:s'));
            session_unset();
        } else {
            $view->setHttpResponseCode(400);
            $message = self::MISSING_DATA_MESSAGE;
        }

        $view->data = [
            'message' => $message,
        ];
        return $view;
    }
}
