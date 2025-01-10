<?php

namespace App\Models;

class PlayersTable extends AbstractTable
{
    protected function getTableName(): string
    {
        return 'players';
    }

    public function getLeaders($limit = 20): array
    {
        return $this->executeSql(
            "
                SELECT
                    name,
                    play_time_seconds,
                    grid_size,
                    ctime
                FROM players
                ORDER BY grid_size desc, play_time_seconds ASC
                LIMIT :limit
            ",
            [
                ':limit' => $limit,
            ]
        );
    }

    public function addRow(string $name, int $gridSize, int $playTimeSeconds): void
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
                ':play_time_seconds' => (int)$playTimeSeconds,
                ':date' => date('Y-m-d H:i:s'),
            ]
        );
    }
}
