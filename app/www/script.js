var gameStart = null;

function makeMove(buttonId) {
  if(gameStart == null)
      gameStart = new Date();

  setButtonsValue(buttonId, 'X');
  makeOpponentsTurn();
}

function updateLeaderBoard(name, gridSize, duration) {
  duration = duration / 1000; // Convert to seconds.
  fetch(
    '/leaderboard/update',
    {
      method: "POST",
      headers: {
        "Accept": "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ 
        name: name,
        gridSize: gridSize,
        duration: duration,
      }),
    }
  )
  .then((response) => {
    if (response.ok) {
      console.log(response)
      window.location.href = '/leaderboard';
    }
  })
}


function makeOpponentsTurn() {
  const matrix = [];

  let row = 1;
  let col = 1;
  let rowTexts = [];
  do {
    const buttonId = `game_grid_${row}_${col}`;

    // Very end of the matrix.
    if (document.getElementById(buttonId) == null && col === 1) {
      break;
    }

    // End of the row.
    if (document.getElementById(buttonId) == null) {
      matrix.push(rowTexts);
      row++;
      col = 1;
      rowTexts = [];
      continue;
    }

    rowTexts.push(document.getElementById(buttonId).innerText);
    col++;
  } while (true);

  fetch(
    '/index/opponents-turn',
    {
      method: "POST",
      headers: {
        "Accept": "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ matrix: matrix }),
    }
  )
  .then((response) => {
    if (response.ok) {
      return response.json();
    }
    return Promise.reject(response); // 2. reject instead of throw
  })
    .then((json) => {
      let is_game_over = json.is_game_over;
      let is_player_win = json.is_player_win;
      let is_computer_win = json.is_computer_win;

      if (!is_game_over || !is_player_win) {
        let row = json.row + 1;
        let col = json.col + 1;
        const buttonId = `game_grid_${row}_${col}`;
        setButtonsValue(buttonId, 'O');
      }

      if (is_game_over) {
        let gameEnd = new Date();
        document
          .querySelectorAll("#game_grid button")
          .forEach(  button => {
            button.disabled = true;
         }
        );

        if (is_player_win) {
          name = prompt('Congratulations, you won!. Please provide your name for the leaderboard.');
          updateLeaderBoard(name, matrix.length, gameEnd - gameStart);
        }
        else if (is_computer_win) {
          alert('Computer won!');
        }
        else {
          alert('Nobody won :(');
        }
      }
    })
}

function setButtonsValue(buttonId, text) {
  document.getElementById(buttonId).innerText = text;
  document.getElementById(buttonId).disabled = true;
}
