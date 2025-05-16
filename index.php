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

    .main-container {
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
      resize: both;
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

    @media (max-width: 600px) {
      .main-container {
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

      .btn-red {
        font-size: 1rem;
        padding: 0.6rem 1rem;
      }
    }
  </style>
</head>

<body>
  <div class="main-container">
    <div class="big-title">YSEレジsystem</div>
    <form action="update.php" method="post" autocomplete="off">
      <!-- Дэлгэц дээрх тоог харуулах хэсэг -->
      <textarea id="display" name="price" class="display" readonly></textarea>

      <div class="buttons">
        <?php
        $buttons = [
          '1', '2', '3', 'AC',
          '4', '5', '6', '+',
          '7', '8', '9', '×',
          '0', '00', '/', '=',
          '.', '-', 'DEL', 'Tax'
        ];
        foreach ($buttons as $val) {
          $safeVal = htmlspecialchars($val, ENT_QUOTES);
          echo "<button type='button' onclick=\"handleClick('$safeVal', event)\">$val</button>";
        }
        ?>
      </div>

      <div class="actions">
        <a href="barcode.php" class="btn-red">バーコード入力</a>
        <a href="commit.php" class="btn-red">計上</a>
        <a href="sales.php" class="btn-red">売上</a>
      </div>
    </form>
  </div>
  <script src="js/app.js"></script>
</body>

</html>