<?php

/**
 * @var \App\Views\LeaderboardView $this
 * @see \App\Controllers\IndexController::chartAjaxAction()
 */

?>

<div class="row content-container col-xs-12">
    <h2 class="text-4xl font-extrabold dark:text-white mb-4"><small class="ms-2 font-semibold text-gray-500 dark:text-gray-400">
        Total number of players: <?= $this->total_players ?> <span class="italic text-sm">(Only the top 20 are shown in the leaderboard)</span>
    </small></h1>
    <table class="w-full justify-center text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3  text-right">
                    Position
                </th>
                <th scope="col" class="px-6 py-3">
                    Name
                </th>
                <th scope="col" class="px-6 py-3 text-right">
                    Duration (Seconds)
                </th>
                <th scope="col" class="px-6 py-3">
                    Grid Size
                </th>
                <th cope="col" class="px-6 py-3">
                    Played at
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->players as $index => $player) : ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class=" text-right px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <?= $index + 1 ?>
                    </th>
                    <td class="px-6 py-4">
                        <?= $player['name'] ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <?= $player['play_time_seconds'] ?>
                    </td>
                    <td class="px-6 py-4">
                        <?= $player['grid_size'] ?>x<?= $player['grid_size'] ?>
                    </td>
                    <td class="px-6 py-4">
                        <?= $player['ctime'] ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
