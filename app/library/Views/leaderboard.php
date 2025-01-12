<?php

/**
 * @var \App\Views\LeaderboardView $this
 * @see \App\Controllers\IndexController::chartAjaxAction()
 */

?>
<div class="toolbar">
    <strong>Showing the top <?php echo $this->showNumberOfPlayers; ?></strong> out of <?php echo $this->numberOfPlayers; ?> players in total
</div>
<div class="content-container">
    <table id="leaderboard">
        <thead><td>Rank</td><td>Name</td><td>Play time</td><td>Grid size</td><td>Time</td></thead>
        <?php if (!empty($this->players)) { ?>
        <?php foreach ($this->players as $index => $player) { ?>
            <tr>
                <td>
                    <?php echo $index+1; ?>
                </td>
                <td>
                    <?php echo $player['name']; ?>
                </td>
                <td>
                    <?php echo $player['play_time_seconds']; ?>
                </td>
                <td>
                    <?php echo $player['grid_size']; ?>
                </td>
                <td>
                    <?php echo $player['ctime']; ?>
                </td>
            </tr>
        <?php }} else { ?>
            <tr><td colspan="5">No results as of yet</td></tr>
        <?php } ?>
    </table>
</div>
