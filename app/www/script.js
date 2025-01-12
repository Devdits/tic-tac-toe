let isFirstMove = true;
let timerInterval;
let playTimeSeconds = 1;

window.onload = () => {
  toggleButtons();

  document.getElementById("player_name")?.addEventListener("change", () => {
    toggleButtons();
  });
};

function toggleButtons() {
  document.querySelectorAll("#game_grid button").forEach((button) => {
    button.disabled = document.getElementById("player_name")?.value.trim() === "";
  });
}

function startTimer() {
  timerInterval = setInterval(() => {
    playTimeSeconds++;
  }, 1000);
}

function stopTimer() {
  clearInterval(timerInterval);
}

function makeMove(buttonId) {
if (isFirstMove) {
    isFirstMove = false;
    startTimer();
  }

  setButtonsValue(buttonId, 'X');
  makeOpponentsTurn();
}

async function makeOpponentsTurn() {
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
    .then(async (json) => {
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
        stopTimer();

        document
          .querySelectorAll("#game_grid button")
          .forEach(  button => {
            button.disabled = true;
         }
        );

        if (is_player_win) {
          alert('Congratulations, you won!');
          await insertWinner();
        }
        else if (is_computer_win) {
          alert('Computer won!');
        }
        else {
          alert('Nobody won :(');
        }

        window.location.href = "/leaderboard"; // Good enough for MVP.
      }
    })
}

function setButtonsValue(buttonId, text) {
  document.getElementById(buttonId).innerText = text;
  document.getElementById(buttonId).disabled = true;
}

async function insertWinner() {
    return fetch("/index/insert-winner", {
      method: "POST",
      headers: {
        "Accept": "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        player_name: document.getElementById("player_name")?.value,
        grid_size: document.getElementById("grid_size")?.value,
        play_time_seconds: playTimeSeconds,
      }),
    })
      .then((response) => {
        if (response.ok) {
          return response.json();
        }
        return Promise.reject(response);
      })
      .then((json) => {
        if (json.success) {
            return json;
        }
        
        console.error("Insertion of winner failed:", json.message || "Unknown error");
        return Promise.reject(json);
      });
  }
