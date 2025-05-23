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
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
