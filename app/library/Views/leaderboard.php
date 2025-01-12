<?php

/**
 * @var \App\Views\LeaderboardView $this
 * @see \App\Controllers\IndexController::chartAjaxAction()
 */

?>
<div class="toolbar">
    <div>
        <strong>Showing the top <?php echo $this->showNumberOfPlayers; ?></strong> out of <?php echo $this->numberOfPlayers; ?> players in total
    </div>
    <?php if ($this->playerWins) { ?>
        <div id ="player_registration">
            <strong>Congratulations you won! Please register yourself.</strong>
            <form method="POST" action="/addPlayer">
                <div>
                    <label for="player_name">Players name</label>
                    <input
                            type="text"
                            min="3"
                            max="20"
                            name="player_name"
                            id="player_name"
                    />
                    <button id="register_result" onclick="registerResults(event)">Register my result</button>
                </div>
                <span id="register_results_validation" class="validation-message"></span>
            </form>
        </div>
    <?php } ?>
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
