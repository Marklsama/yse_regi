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
            hasDot = false; // 小数点フラグをリセット
        }
    } else if (value === "=") {
        if (current && operator !== null) {
            let right = parseFloat(current);
            if (operator === "+") total += right;
            if (operator === "-") total -= right;
            if (operator === "×") total *= right;
            if (operator === "/") {
                if (right === 0) {
                    current = "Error"; // ゼロ除算エラー
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
            total = parseFloat(current); // 合計を更新
        }
    } else if (value === "DEL") {
        current = current.slice(0, -1);
    }
    updateDisplay();
}

document.addEventListener("keydown", function (event) {
    const activeElement = document.activeElement;

    // Зөвхөн калькуляторын display дээр фокус байх үед л калькуляторын үйлдэл хийх
    if (activeElement && activeElement.id === "display") {
        event.preventDefault();

        const key = event.key;
        if (!isNaN(key)) handleClick(key, event);
        else if (key === "+") handleClick("+", event);
        else if (key === "-") handleClick("-", event);
        else if (key === "*") handleClick("×", event);
        else if (key === "/") handleClick("/", event);
        else if (key === "Enter" || key === "=") handleClick("=", event);
        else if (key === "Escape") handleClick("AC", event);
        else if (key === ".") handleClick(".", event);
        else if (key === "Backspace") handleClick("DEL", event);
    }
    // Бусад input, textarea дээр ямар ч үйлдэл хийхгүй (event.preventDefault() хийхгүй)
});
