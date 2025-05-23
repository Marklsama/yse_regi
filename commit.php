<?php
require_once 'db.php';
// barcodeItems-ийг хамгийн эхэнд авна
$barcodeItems = [];
$stmt = $pdo->query("SELECT * FROM barcodes");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $barcodeItems[] = $row;
}

// POST хүсэлтээр sales_items хүснэгтэд cart-ийн бүх барааг хадгалах
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (is_array($data) && count($data) > 0) {
        foreach ($data as $item) {
            $stmt = $pdo->prepare("INSERT INTO sales_items (product_name, quantity, price, sale_date) VALUES (?, ?, ?, NOW())");
            $stmt->execute([
                $item['name'],
                $item['qty'],
                $item['price']
            ]);
        }
        echo "OK";
        exit;
    }
    echo "NG";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>計上 | YSEレジsystem</title>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/commit.css" />
</head>
<body>
  <div class="main-container">
    <div class="big-title">計上</div>
    <div class="flex-container">
      <!-- Тооны машин (index.php шиг харагдуулсан) -->
      <div class="calc-panel">
        <input type="text" id="calc-display" class="display" value="0" readonly>
        <div class="buttons">
          <!-- Тооны машин товчлуурууд -->
          <button onclick="calcInput('7')" type="button">7</button>
          <button onclick="calcInput('8')" type="button">8</button>
          <button onclick="calcInput('9')" type="button">9</button>
          <button onclick="calcOp('/')" type="button">÷</button>
          <button onclick="calcInput('4')" type="button">4</button>
          <button onclick="calcInput('5')" type="button">5</button>
          <button onclick="calcInput('6')" type="button">6</button>
          <button onclick="calcOp('*')" type="button">×</button>
          <button onclick="calcInput('1')" type="button">1</button>
          <button onclick="calcInput('2')" type="button">2</button>
          <button onclick="calcInput('3')" type="button">3</button>
          <button onclick="calcOp('-')" type="button">−</button>
          <button onclick="calcInput('0')" type="button">0</button>
          <button onclick="calcInput('00')" type="button">00</button>
          <button onclick="calcOp('+')" type="button">＋</button>
          <button onclick="calcInput('.')" type="button">.</button>
          <button onclick="calcClear()" class="btn-red" type="button">AC</button>
          <button onclick="calcEqual()" class="btn-main" type="button">=</button>
          <button onclick="addToCart()" class="btn-green" type="button">追加</button>
          <button onclick="addTax()" class="btn-gray" type="button">tax</button>
        </div>
      </div>
      <!-- Баруун талын сагс/барааны жагсаалт -->
      <div class="cart-panel">
        <div class="cart-title">レジを入力してください</div>
        <div class="cart-list" id="cart-list"></div>
        <button class="btn-main" onclick="confirmCart()" type="button">確定</button>
        <div class="cart-question">売上計上しますか？</div>
        <div class="cart-total" id="cart-total">0 <span class="cart-total-unit">円（税込）</span></div>
        <button class="btn-main btn-green" onclick="commitCart()" type="button">計上</button>
        <button class="btn-main btn-gray" onclick="clearCart()" type="button">キャンセル</button>
      </div>
    </div>
    <div class="actions" style="margin-top:2rem;">
      <a href="index.php" class="btn-red">戻る</a>
    </div>
  </div>

  <!-- Бараа сонгох modal -->
  <div id="select-product-modal" style="display:none; position:fixed; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:2rem; border-radius:8px; min-width:300px; max-width:90vw;">
      <h3 style="margin-top:0;">商品を選択してください</h3>
      <div id="select-product-list"></div>
      <button onclick="closeProductModal()" class="btn-red" style="margin-top:1rem;">キャンセル</button>
    </div>
  </div>

  <!-- Баримт (receipt) харуулах modal -->
  <div id="receipt-modal" style="display:none; position:fixed; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:2rem; border-radius:8px; min-width:300px; max-width:90vw;">
      <h3 style="margin-top:0;">レシート</h3>
      <div id="receipt-content"></div>
      <button onclick="closeReceipt()" class="btn-red" style="margin-top:1rem;">閉じる</button>
      <button onclick="printReceipt()" class="btn-main" style="margin-top:1rem;">印刷</button>
    </div>
  </div>

  <script>
    // barcodeItems массивыг хамгийн эхэнд зарлана
    const barcodeItems = <?= json_encode($barcodeItems, JSON_UNESCAPED_UNICODE) ?>;

    // Калькуляторын логик (бүтэн тоо болон бутархай дэмжинэ)
    let calcValue = "";
    let calcBuffer = "";
    let calcOperator = "";
    let calcResultShown = false;

    function calcInput(val) {
      if (calcResultShown) {
        calcValue = "";
        calcResultShown = false;
      }
      // "." нэгээс олон удаа оруулахгүй
      if (val === ".") {
        if (calcValue.includes(".")) return;
        if (calcValue === "") calcValue = "0";
      }
      if (calcValue === "0" && val !== ".") calcValue = "";
      calcValue += val;
      document.getElementById('calc-display').value = calcValue;
    }

    function calcOp(op) {
      if (calcValue === "" && calcBuffer === "") return;
      if (calcBuffer !== "" && calcOperator && calcValue !== "") {
        calcBuffer = String(calcEval());
        calcValue = "";
      } else if (calcValue !== "") {
        calcBuffer = calcValue;
        calcValue = "";
      }
      calcOperator = op;
      document.getElementById('calc-display').value = calcBuffer;
    }

    function calcEqual() {
      if (calcBuffer !== "" && calcOperator && calcValue !== "") {
        let result = calcEval();
        document.getElementById('calc-display').value = result;
        calcValue = String(result);
        calcBuffer = "";
        calcOperator = "";
        calcResultShown = true;
      }
    }

    function calcEval() {
      let a = parseFloat(calcBuffer) || 0;
      let b = parseFloat(calcValue) || 0;
      switch (calcOperator) {
        case "+": return +(a + b).toFixed(6);
        case "-": return +(a - b).toFixed(6);
        case "*": return +(a * b).toFixed(6);
        case "/": return b !== 0 ? +(a / b).toFixed(6) : 0;
        default: return b;
      }
    }

    function calcClear() {
      calcValue = "";
      calcBuffer = "";
      calcOperator = "";
      calcResultShown = false;
      document.getElementById('calc-display').value = "0";
    }

    // Сагсны логик
    let cart = [];
    function addToCart() {
      let price = parseInt(calcValue, 10);
      if (!price || price <= 0) return;

      let matchedProducts = barcodeItems.filter(item => parseInt(item.price, 10) === price);

      if (matchedProducts.length === 0) {
        let name = "未登録商品";
        let found = cart.find(item => item.price === price && item.name === name);
        if (found) found.qty++;
        else cart.push({ price, qty: 1, name });
        calcClear();
        renderCart();
      } else if (matchedProducts.length === 1) {
        let name = matchedProducts[0].product;
        let found = cart.find(item => item.price === price && item.name === name);
        if (found) found.qty++;
        else cart.push({ price, qty: 1, name });
        calcClear();
        renderCart();
      } else {
        showProductSelectModal(matchedProducts, price);
      }
    }

    function showProductSelectModal(products, price) {
      let html = '';
      products.forEach(item => {
        html += `<button class="btn-main" style="margin:0.5rem 0; width:100%;" onclick="selectProductForCart('${item.product.replace(/'/g, "\\'")}', ${price})">${item.product}</button><br>`;
      });
      document.getElementById('select-product-list').innerHTML = html;
      document.getElementById('select-product-modal').style.display = 'flex';
    }

    function selectProductForCart(productName, price) {
      let found = cart.find(item => item.price === price && item.name === productName);
      if (found) found.qty++;
      else cart.push({ price, qty: 1, name: productName });
      calcClear();
      renderCart();
      closeProductModal();
    }

    function closeProductModal() {
      document.getElementById('select-product-modal').style.display = 'none';
    }

    function renderCart() {
      let html = "";
      let total = 0;
      cart.forEach((item, idx) => {
        let subtotal = item.price * item.qty;
        total += subtotal;
        html += `<div class="cart-item">
          <span class="cart-item-label">${item.name} (${item.price} × ${item.qty})</span>
          <span class="cart-subtotal">小計：${subtotal} 円</span>
          <button class="qty-btn" onclick="changeQty(${idx},-1)" type="button">-</button>
          <button class="qty-btn" onclick="changeQty(${idx},1)" type="button">+</button>
          <button class="del-btn btn-red" onclick="removeItem(${idx})" type="button">削除</button>
        </div>`;
      });
      document.getElementById('cart-list').innerHTML = html || '<div style="color:#888;">商品がありません。</div>';
      document.getElementById('cart-total').innerHTML = total + ' <span class="cart-total-unit">円（税込）</span>';
    }
    function changeQty(idx, delta) {
      cart[idx].qty += delta;
      if (cart[idx].qty <= 0) cart.splice(idx, 1);
      renderCart();
    }
    function removeItem(idx) {
      cart.splice(idx, 1);
      renderCart();
    }
    function clearCart() {
      cart = [];
      renderCart();
    }
    function confirmCart() {
      alert("確定しました！");
    }

    // Баримт (receipt) харуулах функц
    function showReceipt(cartData) {
      let html = '<table style="width:100%;border-collapse:collapse;">';
      html += '<tr><th style="text-align:left;">商品名</th><th style="text-align:right;">数量</th><th style="text-align:right;">金額</th></tr>';
      let total = 0;
      cartData.forEach(item => {
        let subtotal = item.price * item.qty;
        total += subtotal;
        html += `<tr>
          <td>${item.name}</td>
          <td style="text-align:right;">${item.qty}</td>
          <td style="text-align:right;">${subtotal} 円</td>
        </tr>`;
      });
      html += `<tr><td colspan="2" style="text-align:right;font-weight:bold;">合計</td><td style="text-align:right;font-weight:bold;">${total} 円</td></tr>`;
      html += '</table>';
      document.getElementById('receipt-content').innerHTML = html;
      document.getElementById('receipt-modal').style.display = 'flex';
    }

    function closeReceipt() {
      document.getElementById('receipt-modal').style.display = 'none';
    }

    function printReceipt() {
      let printContents = document.getElementById('receipt-content').innerHTML;
      let win = window.open('', '', 'width=400,height=600');
      win.document.write('<html><head><title>レシート</title></head><body>' + printContents + '</body></html>');
      win.document.close();
      win.print();
    }

    function commitCart() {
      if (cart.length === 0) {
        alert("商品を追加してください。");
        return;
      }
      fetch('commit.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(cart),
      })
      .then(response => response.text())
      .then(data => {
        showReceipt(cart); // Баримт харуулах
        clearCart();
      })
      .catch(error => {
        console.error('Error:', error);
        alert("エラーが発生しました。");
      });
    }

    renderCart();

    // Тооны машинд keyboard-оор удирдах event listener нэмэх
    document.addEventListener('keydown', function(e) {
      // Тоо оруулах
      if (e.key >= '0' && e.key <= '9') {
        if (calcValue === "0") calcValue = "";
        calcInput(e.key);
        e.preventDefault();
      } else if (e.key === '.' || e.key === ',') {
        calcInput('.');
        e.preventDefault();
      } else if (e.key === 'a' || e.key === 'A' || e.key === 'Escape') {
        calcClear();
        e.preventDefault();
      } else if (e.key === 'Enter' || e.key === '=') {
        calcEqual();
        e.preventDefault();
      } else if (e.key === '+') {
        calcOp('+');
        e.preventDefault();
      } else if (e.key === '-') {
        calcOp('-');
        e.preventDefault();
      } else if (e.key === '*' || e.key === 'x' || e.key === 'X') {
        calcOp('*');
        e.preventDefault();
      } else if (e.key === '/') {
        calcOp('/');
        e.preventDefault();
      } else if (e.key === 'Backspace') {
        if (calcValue.length > 0) {
          calcValue = calcValue.slice(0, -1);
          document.getElementById('calc-display').value = calcValue === "" ? "0" : calcValue;
        }
        e.preventDefault();
      } else if (e.key === 't' || e.key === 'T') {
        addTax();
        e.preventDefault();
      }
      // 00 shortcut
      if (e.shiftKey && e.key === '0') {
        calcInput('00');
        e.preventDefault();
      }
    });
  </script>
</body>
</html>