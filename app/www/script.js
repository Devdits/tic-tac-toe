function makeMove(buttonId) {
  setButtonsValue(buttonId, 'X');
  makeOpponentsTurn();
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

      if (!is_game_over || !is_player_win) {
        let row = json.row + 1;
        let col = json.col + 1;
        const buttonId = `game_grid_${row}_${col}`;
        setButtonsValue(buttonId, 'O');
      }

      if (is_game_over) {
        document
          .querySelectorAll("#game_grid button")
          .forEach(  button => {
            button.disabled = true;
         }
        );
        window.location.href = window.location.origin + '/leaderboard';
      }
    })
}

function setButtonsValue(buttonId, text) {
  document.getElementById(buttonId).innerText = text;
  document.getElementById(buttonId).disabled = true;
}

const registerResults = (event) => {
  event.preventDefault()
  let player_name = document.getElementById('player_name');
  fetch(
    '/leaderboard/register-results',
    {
      method: "POST",
      headers: {
        "Accept": "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ player_name: player_name.value }),
    }
  ).then((response) => {
    if (response.ok) {
      window.location.href = window.location.origin + '/leaderboard';
    }
    return response.json();
  }).then((data) => {
    const validationMessage = document.getElementById('register_results_validation');
    validationMessage.innerHTML = data.message;
    validationMessage.style.display = 'block';
  })
}
