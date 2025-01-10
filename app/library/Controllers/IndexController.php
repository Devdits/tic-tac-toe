<?php

namespace App\Controllers;

use App\GameLogic;
use App\Views\IndexView;
use App\Views\AbstractView;
use App\Views\JsonView;

class IndexController extends Controller implements ControllerInterface
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

    /**
     * It is used in Ajax requests.
     * @noinspection PhpUnused
     */
    public function opponentsTurnAction(): AbstractView
    {
        // Todo: Ask on StakeOverflow if it's good enough.

        $request = $this->getJsonRequest();

        $matrix = $request['matrix'] ?? [];

        $gameLogic = new GameLogic($matrix);

        $isGameOver = $gameLogic->isGameOver();
        $isPlayerWin = $gameLogic->doWeHaveWinner();
        $isComputerWin = false;
        $row = 0;
        $col = 0;
        if (!$isPlayerWin && $gameLogic->isFreeCellsLeft()) {
            list($row, $col) = $gameLogic->makeMove();
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


}
