# Hướng Dẫn Chuyển Database Từ Railway Sang TiDB Cloud

> Tài liệu này ghi lại toàn bộ các bước thực tế đã thực hiện để migrate database MySQL từ Railway sang TiDB Cloud Serverless cho dự án Laravel deploy trên Render.

---

## Bối Cảnh

- **App:** Laravel 12, deploy trên **Render** (Docker)
- **DB cũ:** MySQL trên **Railway** (hết free trial $5 → bị suspend)
- **DB mới:** MySQL compatible trên **TiDB Cloud Serverless** (miễn phí)
- **Máy local:** Windows + XAMPP

---

## Vấn Đề Gặp Phải

App trả về `500 Server Error`. Nguyên nhân:
```
PDO::__construct(): Error while reading greeting packet
```
→ Railway hết $5 credit → MySQL bị suspend → Laravel không kết nối được DB.

---

## Tổng Quan Các Bước

```
1. Nâng Railway lên Hobby ($5) để truy cập lại DB
2. Dump database ra file SQL bằng PHP script
3. Tạo TiDB Cloud Serverless account + cluster
4. Tạo database mới trên TiDB
5. Import data vào TiDB bằng PHP script
6. Cập nhật env vars trên Render
7. Xác nhận kết nối thành công
```

---

## Bước 1: Nâng Railway Lên Hobby Plan

Railway trial expired → không thể start lại MySQL mà không trả tiền.

1. Vào Railway Dashboard → click **"Upgrade your plan to continue deploying"**
2. Chọn **Hobby Plan ($5/tháng)** → thêm thẻ thanh toán → confirm
3. MySQL service sẽ tự động start lại
4. Vào MySQL service → tab **"Connect"** → **"Public Network"** → lấy connection string:
   ```
   mysql://root:PASSWORD@interchange.proxy.rlwy.net:PORT/railway
   ```

---

## Bước 2: Dump Database Bằng PHP Script

> ⚠️ Không dùng `mysqldump` của XAMPP được vì XAMPP dùng MySQL client cũ, không hỗ trợ `caching_sha2_password` của MySQL 9.x trên Railway.

Tạo file `db_dump.php` trong thư mục project:

```php
<?php
$host     = 'interchange.proxy.rlwy.net'; // từ Railway
$port     = 'PORT';                        // public port từ Railway
$dbname   = 'railway';
$username = 'root';
$password = 'RAILWAY_PASSWORD';

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage() . "\n");
}

$output = "-- Database Backup\n";
$output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
$output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

foreach ($tables as $table) {
    echo "Dumping table: $table\n";

    $createStmt = $pdo->query("SHOW CREATE TABLE `$table`")->fetch();
    $output .= "DROP TABLE IF EXISTS `$table`;\n";
    $output .= $createStmt[1] . ";\n\n";

    $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
    if ($rows) {
        $columns = array_keys($rows[0]);
        $colList = implode('`, `', $columns);

        foreach ($rows as $row) {
            $values = array_map(function($v) use ($pdo) {
                return is_null($v) ? 'NULL' : $pdo->quote($v);
            }, array_values($row));
            $valList = implode(', ', $values);
            $output .= "INSERT INTO `$table` (`$colList`) VALUES ($valList);\n";
        }
        $output .= "\n";
    }
}

$output .= "SET FOREIGN_KEY_CHECKS=1;\n";
file_put_contents(__DIR__ . '/backup.sql', $output);
echo "\nDone! File saved: backup.sql\n";
```

Chạy:
```cmd
cd C:\xampp\htdocs\TÊN_PROJECT
php db_dump.php
```

File `backup.sql` xuất hiện trong thư mục project. **Xóa `db_dump.php` ngay sau khi dùng xong** (chứa password).

---

## Bước 3: Tạo TiDB Cloud Serverless

1. Google **"TiDB Cloud"** → đăng ký free account (không cần thẻ)
2. Tạo **Serverless Cluster** → chọn region gần nhất (Tokyo nếu ở VN)
3. Chờ cluster **Active**
4. Click **"Connect"** (góc trên phải) → **"Generate Password"** → **copy và lưu password ngay** (chỉ hiện 1 lần)
5. Ghi lại connection details:
   ```
   HOST:     gateway01.ap-northeast-1.prod.aws.tidbcloud.com
   PORT:     4000
   USERNAME: xxxx.root  (dạng random.root)
   PASSWORD: (vừa generate)
   ```

---

## Bước 4: Tạo Database Mới Trên TiDB

Vào **SQL Editor** → chạy:

```sql
CREATE DATABASE tên_database;
```

Đặt tên tùy ý (ví dụ: `coffeeblend`, `lms`).

---

## Bước 5: Import Data Vào TiDB Bằng PHP Script

> ⚠️ TiDB Cloud bắt buộc SSL. `mysql` client của XAMPP không hỗ trợ → dùng PHP thay thế.

Tạo file `db_import.php`:

```php
<?php
$host     = 'gateway01.ap-northeast-1.prod.aws.tidbcloud.com';
$port     = '4000';
$dbname   = 'tên_database'; // database vừa tạo ở Bước 4
$username = 'xxxx.root';
$password = 'TIDB_PASSWORD';

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_SSL_CA => 'C:/xampp/apache/bin/curl-ca-bundle.crt',
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        ]
    );
    echo "Connected to TiDB successfully!\n";
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage() . "\n");
}

$sql = file_get_contents(__DIR__ . '/backup.sql');

$statements = array_filter(
    array_map('trim', explode(";\n", $sql)),
    fn($s) => $s !== '' && !str_starts_with($s, '--')
);

$total = count($statements);
$done  = 0;

foreach ($statements as $statement) {
    try {
        $pdo->exec($statement);
        $done++;
        if ($done % 10 === 0) echo "Progress: $done/$total\n";
    } catch (Exception $e) {
        echo "Warning (skipped): " . $e->getMessage() . "\n";
    }
}

echo "\nDone! $done/$total statements imported.\n";
```

Chạy:
```cmd
php db_import.php
```

**Xóa `db_import.php` ngay sau khi dùng xong** (chứa password).

---

## Bước 6: Cập Nhật Env Vars Trên Render

Vào **Render Dashboard → Web Service → Environment** → cập nhật/thêm các biến:

```
DB_HOST=gateway01.ap-northeast-1.prod.aws.tidbcloud.com
DB_PORT=4000
DB_DATABASE=tên_database
DB_USERNAME=xxxx.root
DB_PASSWORD=TIDB_PASSWORD
MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt
```

> **Lưu ý:** `MYSQL_ATTR_SSL_CA` trỏ đến CA cert trong Docker container (Debian), **không phải** máy local. Laravel 12's `config/database.php` đã hỗ trợ biến này sẵn.

Save → Render tự động redeploy.

---

## Bước 7: Xác Nhận Kết Nối Thành Công

Vào **TiDB Cloud → SQL Editor** → chạy:

```sql
USE tên_database;
SELECT * FROM sessions;
```

Nếu thấy session mới xuất hiện sau khi truy cập web → app đang kết nối TiDB thành công.

---

## Lỗi Thường Gặp

| Lỗi | Nguyên nhân | Fix |
|-----|-------------|-----|
| `Error while reading greeting packet` | Railway DB bị suspend | Nâng Hobby plan hoặc migrate sang TiDB |
| `caching_sha2_password could not be loaded` | XAMPP MySQL client cũ | Dùng PHP script thay mysqldump |
| `Connections using insecure transport are prohibited` | TiDB bắt buộc SSL | Thêm `MYSQL_ATTR_SSL_CA` vào PDO options |
| `unknown variable 'ssl-mode=REQUIRED'` | XAMPP mysql client cũ | Dùng PHP script thay mysql client |
| 500 sau khi deploy lên Render | Laravel không SSL với TiDB | Thêm `MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt` vào Render env vars |

---

## Lưu Ý Quan Trọng

- **Không commit** `db_dump.php` hay `db_import.php` lên GitHub (chứa password)
- **Không commit** file `backup.sql` lên GitHub (chứa toàn bộ data)
- TiDB Cloud Serverless free tier: **5 GB storage**, không giới hạn thời gian
- Có thể tạo nhiều database trong cùng 1 cluster → dùng cho nhiều project
- Sau khi migrate xong Railway, có thể **xóa project Railway** để không bị charge thêm

---

_Thực hiện: 2026-03-21 · CoffeeBlend Laravel Project_
