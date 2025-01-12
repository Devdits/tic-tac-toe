<?php

namespace App\Models;

class PlayersTable extends AbstractTable
{
    const NUMBER_OF_PLAYERS_SHOWN = 20;
    protected function getTableName(): string
    {
        return 'players';
    }

    public function getLeaders(): array
    {
        $limit = self::NUMBER_OF_PLAYERS_SHOWN;
        return $this->executeSql(
            "
                SELECT
                    name,
                    play_time_seconds,
                    grid_size,
                    ctime
                FROM players
                ORDER BY grid_size DESC, play_time_seconds ASC
                LIMIT $limit
            ",
            []
        );
    }

    public function addRow(string $name, int $gridSize, int $playTimeSeconds, string $date): void
    {
        $this->executeSql(
            "
                INSERT INTO players
                    (name, grid_size, play_time_seconds, ctime)
                VALUE
                    (:name, :grid_size, :play_time_seconds, :date)
            ",
            [
                ':name' => $name,
                ':grid_size' => $gridSize,
                ':play_time_seconds' => $playTimeSeconds,
                ':date' => $date,
            ]
        );
    }
}
