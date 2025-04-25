
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>YSEレジ</title>
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <div class="calculator">
    <form action="update.php" method="post">
      <!-- Дэлгэц дээрх тоог харуулах хэсэг -->
      <input type="text" id="display" name="price" class="display" readonly />

      <div class="buttons">
        <!-- Тооны болон үйлдлийн 20 товчлуурууд -->
        <?php
        $buttons = [
          '1',
          '2',
          '3',
          'AC', // Бүгдийг цэвэрлэх
          '4',
          '5',
          '6',
          '+', // Нэмэх үйлдэл
          '7',
          '8',
          '9',
          '×', // Үржих үйлдэл
          '0',
          '00',
          '/', // Хуваах үйлдэл
          '=', // Тэнцүү
          '.', // Аравтын бутархай
          '-', // Хасах үйлдэл
          'DEL', // Арын тоог устгах
          'Tax' // Татвар тооцоолох
        ];
        foreach ($buttons as $val) {
          $safeVal = htmlspecialchars($val, ENT_QUOTES);
          echo "<button type='button' onclick=\"handleClick('$safeVal', event)\">$val</button>";
        }
        ?>
      </div>

      <div class="actions">
        <!-- Баркод оруулах -->
        <a href="barcode.php" class="btn-red">バーコード入力</a>
        <!-- Тооцоолол хийх -->
        <a href="commit.php" class="btn-red">計上</a>
        <!-- Борлуулалтын мэдээлэл харах -->
        <a href="sales.php" class="btn-red">売上</a>
      </div>
    </form>
  </div>

  <script src="js/app.js"></script>
</body>

</html>