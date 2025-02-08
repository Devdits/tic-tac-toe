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
        $gridSize = $this->getGridSize();

        $view = new IndexView();
        $view->gridSize = $gridSize;

        return $view;
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

    private function getCurrentTimeString(): string
    {
        return date('Y-m-d h:i:s');
    }

    private function calculateElapsedTime($startTimeU): int
    {
        if (!is_numeric($startTimeU)) {
            return 0;
        }

        $now = (int) date('U');
        return ($now - (int) $startTimeU);
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
        $playerName = $request['player_name'] ?? 'Unknown player';
        $gridSize = $request['grid_size'] ?? 3;
        $startTime = $request['start_time'] ?? 0;

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

        if ($isPlayerWin) {
            $playersTable = new PlayersTable();
            $playersTable->addRow($playerName, $gridSize, $this->calculateElapsedTime($startTime), $this->getCurrentTimeString());
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


}
