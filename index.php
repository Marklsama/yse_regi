<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>YSEレジsystem</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    body {
      background: #232323;
      color: #fff;
      font-family: 'Segoe UI', Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    .pos-container {
      max-width: 480px;
      margin: 48px auto 0 auto;
      background: #181818;
      border-radius: 24px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
      padding: 2.5rem 2rem 2rem 2rem;
    }

    .big-title {
      text-align: center;
      font-size: 2.8rem;
      font-weight: bold;
      letter-spacing: 0.12em;
      margin-bottom: 2.5rem;
      color: #ffe082;
      text-shadow: 0 2px 8px #0008;
    }

    .display {
      width: 100%;
      height: 60px;
      font-size: 2rem;
      border-radius: 10px;
      border: none;
      background: #222;
      color: #ffe082;
      margin-bottom: 1.2rem;
      padding: 0.7rem 1rem;
      resize: none;
      box-sizing: border-box;
    }

    .buttons {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 0.7rem;
      margin-bottom: 1.5rem;
    }

    .buttons button {
      font-size: 1.3rem;
      padding: 1rem 0;
      border-radius: 10px;
      border: none;
      background: #a68c2c;
      color: #fff;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.18s;
    }

    .buttons button:hover {
      background: #ffe082;
      color: #222;
    }

    .buttons button:nth-child(4n) {
      background: #b71c1c;
      color: #fff;
    }

    .buttons button:nth-child(4n):hover {
      background: #ff5252;
      color: #fff;
    }

    .actions {
      display: flex;
      gap: 1rem;
      margin-top: 1.5rem;
      justify-content: center;
    }

    .btn-red {
      background: #b71c1c;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 0.8rem 1.7rem;
      font-size: 1.1rem;
      cursor: pointer;
      text-decoration: none;
      text-align: center;
      transition: background 0.2s;
      font-weight: bold;
      letter-spacing: 0.05em;
    }

    .btn-red:hover {
      background: #880000;
    }

    .btn-green {
      background: #27ae60;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 0.8rem 1.7rem;
      font-size: 1.1rem;
      cursor: pointer;
      text-decoration: none;
      text-align: center;
      transition: background 0.2s;
      font-weight: bold;
      letter-spacing: 0.05em;
    }

    .btn-green:hover {
      background: #219150;
    }

    @media (max-width: 600px) {
      .pos-container {
        padding: 1rem 0.5rem;
      }

      .big-title {
        font-size: 2rem;
      }

      .display {
        font-size: 1.2rem;
        height: 40px;
      }

      .buttons button {
        font-size: 1rem;
        padding: 0.7rem 0;
      }

      .btn-red,
      .btn-green {
        font-size: 1rem;
        padding: 0.6rem 1rem;
      }
    }
  </style>
</head>

<body>
  <div class="pos-container">
    <div class="big-title">YSEレジsystem</div>
    <textarea id="display" class="display" readonly></textarea>
    <div class="buttons">
      <button type="button" onclick="handleClick('1', event)">1</button>
      <button type="button" onclick="handleClick('2', event)">2</button>
      <button type="button" onclick="handleClick('3', event)">3</button>
      <button type="button" onclick="handleClick('AC', event)">AC</button>
      <button type="button" onclick="handleClick('4', event)">4</button>
      <button type="button" onclick="handleClick('5', event)">5</button>
      <button type="button" onclick="handleClick('6', event)">6</button>
      <button type="button" onclick="handleClick('+', event)">+</button>
      <button type="button" onclick="handleClick('7', event)">7</button>
      <button type="button" onclick="handleClick('8', event)">8</button>
      <button type="button" onclick="handleClick('9', event)">9</button>
      <button type="button" onclick="handleClick('×', event)">×</button>
      <button type="button" onclick="handleClick('0', event)">0</button>
      <button type="button" onclick="handleClick('00', event)">00</button>
      <button type="button" onclick="handleClick('/', event)">/</button>
      <button type="button" onclick="handleClick('=', event)">=</button>
      <button type="button" onclick="handleClick('.', event)">.</button>
      <button type="button" onclick="handleClick('-', event)">-</button>
      <button type="button" onclick="handleClick('DEL', event)">DEL</button>
      <button type="button" onclick="handleClick('Tax', event)">Tax</button>
    </div>
    <div class="actions">
      <a href="barcode.php" class="btn-red">バーコード入力</a>
      <a href="commit.php" class="btn-green">計上</a>
      <a href="sales.php" class="btn-red">売上</a>
    </div>
  </div>
  <script>
    let current = "";
    let total = 0;
    let operator = null;
    let hasDot = false;

    function updateDisplay(value = null) {
      const display = document.getElementById("display");
      display.value = value !== null ? value : current || "0";
    }

    function handleClick(value, event) {
      event.preventDefault();
      if (!isNaN(value) || value === "00") {
        current += value;
      } else if (value === ".") {
        if (!hasDot) {
          current += ".";
          hasDot = true;
        }
      } else if (value === "AC") {
        current = "";
        total = 0;
        operator = null;
        hasDot = false;
      } else if (value === "+" || value === "-" || value === "×" || value === "/") {
        if (current) {
          total = parseFloat(current);
          current = "";
          operator = value;
          hasDot = false;
        }
      } else if (value === "=") {
        if (current && operator !== null) {
          let right = parseFloat(current);
          if (operator === "+") total += right;
          if (operator === "-") total -= right;
          if (operator === "×") total *= right;
          if (operator === "/") {
            if (right === 0) {
              current = "Error";
            } else {
              total /= right;
            }
          }
          current = total.toFixed(2);
          operator = null;
        }
      } else if (value === "Tax") {
        if (current) {
          current = (parseFloat(current) * 1.1).toFixed(2);
          total = parseFloat(current);
        }
      } else if (value === "DEL") {
        current = current.slice(0, -1);
      }
      updateDisplay();
    }
    updateDisplay();
  </script>
</body>

</html>