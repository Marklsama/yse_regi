-- sales_items хүснэгт
CREATE TABLE sales_items (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,             -- 販売記録の一意の識別子（主キー）
    sale_date DATE NOT NULL,                           -- 販売日（YYYY-MM-DD形式）
    product_name VARCHAR(255) NOT NULL,                -- 商品名（最大255文字）
    quantity INT NOT NULL,                             -- 数量（整数）
    amount DECIMAL(10, 2) NOT NULL,                    -- 金額（小数点以下2桁まで）
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,     -- レコード作成日時
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  -- レコード更新日時
);

-- barcodes хүснэгт
CREATE TABLE barcodes (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,             -- バーコードデータの一意の識別子（主キー）
    barcode VARCHAR(255) NOT NULL,                    -- バーコード
    product VARCHAR(255) NOT NULL,                    -- 商品名
    quantity INT NOT NULL,                             -- 数量
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,     -- レコード作成日時
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  -- レコード更新日時
);

-- sales хүснэгт
CREATE TABLE sales (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,             -- 売上データの一意の識別子（主キー）
    sale_at DATE NOT NULL,                             -- 売上日
    amount DECIMAL(10, 2) NOT NULL,                    -- 売上金額
    receipt_no VARCHAR(255) NOT NULL,                 -- レシート番号
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,     -- レコード作成日時
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  -- レコード更新日時
);

-- commits хүснэгт
CREATE TABLE commits (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,             -- 計上データの一意の識別子（主キー）
    price DECIMAL(10, 2) NOT NULL,                    -- 金額
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,     -- レコード作成日時
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  -- レコード更新日時
);
