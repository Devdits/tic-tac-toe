<?php

namespace App\Controllers;

use App\GameLogic;
use App\Models\PlayersTable;
use App\Views\IndexView;
use App\Views\AbstractView;
use App\Views\JsonView;

class IndexController implements ControllerInterface
{
    public function indexAction(): AbstractView
    {
        $playerName = $this->getPlayerName();
        $gridSize = $this->getGridSize();

        $view = new IndexView();
        $view->playerName = $playerName;
        $view->gridSize = $gridSize;

        return $view;
    }

    private function getPlayerName(): string
    {
        return $_GET['player_name'] ?? 'Player';
    }

    private function getGridSize(): int
    {
        // Simple validation
        $gridSize = intval($_GET['grid_size'] ?? 3);
        if ($gridSize < 3 || $gridSize > 50) {
            $gridSize = 3;
        }
        return $gridSize;
    }

    /**
     * It is used in Ajax requests.
     * @noinspection PhpUnused
     */
    public function opponentsTurnAction(): AbstractView
    {
        // Todo: Ask on StakeOverflow if it's good enough.
        $requestJson = file_get_contents('php://input');
        $request = json_decode($requestJson, true);

        $matrix = $request['matrix'] ?? [];

        $gameLogic = new GameLogic($matrix);

        $isGameOver = $gameLogic->isGameOver();
        $isPlayerWin = $gameLogic->doWeHaveWinner();
        $isComputerWin = false;
        $row = 0;
        $col = 0;
        if (!$isPlayerWin && $gameLogic->isFreeCellsLeft()) {
            list($row, $col) = $gameLogic->findBestMove();
            $gameLogic->setComputersMove($row, $col);
            $isGameOver = $gameLogic->isGameOver();
            $isComputerWin = $gameLogic->doWeHaveWinner();
        }

        $view = new JsonView();
        $view->data = [
            'is_game_over' => $isGameOver,
            'is_player_win' => $isPlayerWin,
            'is_computer_win' => $isComputerWin,
            'row' => $row,
            'col' => $col,
        ];
        return $view;
    }

    /**
     * It is used in Ajax requests.
     * @noinspection PhpUnused
     */
    public function insertWinnerAction(): AbstractView
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
