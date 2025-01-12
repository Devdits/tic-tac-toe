<?php

namespace App\Controllers;

use App\Models\PlayersTable;
use App\Views\AbstractView;
use App\Views\JsonView;
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

    /**
     * It is used in Ajax requests.
     * @noinspection PhpUnused
     */
    public function saveWinnerAction(): AbstractView
    {
        $requestJson = file_get_contents('php://input');
        $request = json_decode($requestJson, true);
        $view = new JsonView();
        $errors = [];

        // Simple validation and sanitization, consider creating generic methods in a service layer later.
        if (empty($request['player_name']) || !is_string($request['player_name']) || strlen($request['player_name']) > 100) {
            $errors['player_name'] = 'Player name is required and must be a string with at most 100 characters.'; // 100 characters as defined in the database.
        } else {
            $playerName = htmlspecialchars(trim($request['player_name']), ENT_QUOTES, 'UTF-8');
        }

        if (!isset($request['grid_size']) || !is_numeric($request['grid_size']) || intval($request['grid_size']) < 3 || intval($request['grid_size']) > 50) {
            $errors['grid_size'] = 'Grid size must be an integer between 3 and 50.';
        } else {
            $gridSize = intval($request['grid_size']);
        }

        if (!isset($request['play_time_seconds']) || !is_numeric($request['play_time_seconds']) || intval($request['play_time_seconds']) <= 0) {
            $errors['play_time_seconds'] = 'Play time must be a positive integer.';
        } else {
            $playTimeSeconds = intval($request['play_time_seconds']);
        }

        if (!empty($errors)) {
            $view->data = [
                'success' => false,
                'errors' => $errors
            ];
        } else {
            // Todo: Confirm insertion success/failure for error handling.
            (new PlayersTable())->addRow($playerName, $gridSize, $playTimeSeconds, date('Y-m-d H:i:s'));

            $view->data = [
                'success' => true
            ];
        }

        return $view;
    }
}
