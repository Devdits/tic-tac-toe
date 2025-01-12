<?php

/**
 * @var \App\Views\LeaderboardView $this
 * @see \App\Controllers\IndexController::chartAjaxAction()
 */

?>

<div class="row content-container col-xs-12">
    <p><b>Total players:</b></p>
    <?= $this->totalPlayers ?>

    <br />
    <br />

    <p><b>Top 20 players:</b></p>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Player name</th>
                <th>Grid size</th>
                <th>Play time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $index = 1;
            foreach ($this->leaders as $leader): ?>
                <tr>
                    <td><?= $index++ ?></td>
                    <td><?= $leader['name'] ?></td>
                    <td><?= $leader['grid_size'] ?></td>
                    <td><?= $leader['play_time_seconds'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
