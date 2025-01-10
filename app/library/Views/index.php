<?php

/**
 * @var App\Views\IndexView $this
 * @see \App\Controllers\IndexController::chartAjaxAction()
 */

?>
<div class="toolbar">
    <form class="flex flex-wrap gap-2 items-center" action="/" method="get">
        <label for="grid_size">Grid size</label>
        <input
            type="number"
            min="3"
            max="20"
            id="grid_size"
            name="grid_size"
            value="<?= $this->gridSize ?>"
        />
        <input type="submit" value="Play" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 w-12 h-8 border border-gray-300 flex items-center justify-center">
    </form>
</div>

<div class="row content-container col-xs-12">
    <div id="game_grid_container">
        <table id="game_grid">
            <?php for ($row = 1; $row <= $this->gridSize; $row++) { ?>
                <tr>
                    <?php
                        for ($col = 1; $col <= $this->gridSize; $col++) {
                            $button_id = 'game_grid_' . $row . '_' . $col;
                            ?>
                        <td>
                            <button class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700w-12 h-12 border border-gray-300 flex items-center justify-center" id="<?= $button_id ?>" onclick="makeMove('<?= $button_id ?>')"></button>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
