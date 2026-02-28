# Hướng Dẫn Deploy CoffeeBlend Laravel

**Stack:** Laravel 12 · PHP 8.2 · MySQL (Railway) · Docker (Render)
**Ngày viết:** 2026-02-27

---

## Mục Lục

1. [Tổng quan kiến trúc](#1-tổng-quan-kiến-trúc)
2. [Chuẩn bị project](#2-chuẩn-bị-project)
3. [Push code lên GitHub](#3-push-code-lên-github)
4. [Tạo database MySQL trên Railway](#4-tạo-database-mysql-trên-railway)
5. [Deploy app lên Render](#5-deploy-app-lên-render)
6. [Cấu hình biến môi trường](#6-cấu-hình-biến-môi-trường)
7. [Kiểm tra sau deploy](#7-kiểm-tra-sau-deploy)
8. [Troubleshooting](#8-troubleshooting)
9. [Re-deploy khi update code](#9-re-deploy-khi-update-code)
10. [Lưu ý quan trọng](#10-lưu-ý-quan-trọng)

---

## 1. Tổng Quan Kiến Trúc

```
GitHub Repo
    │
    ▼
Render.com (Web Service)         Railway.app (MySQL Database)
┌─────────────────────┐          ┌─────────────────────────┐
│  Docker Container   │ ◄──────► │  MySQL 8                │
│  PHP 8.2 + Apache  │          │  Managed DB Service     │
│  Laravel 12         │          └─────────────────────────┘
└─────────────────────┘
```

- **Render** host ứng dụng Laravel trong Docker container, kết nối GitHub để auto-deploy.
- **Railway** cung cấp database MySQL managed (không cần tự setup server DB).

---

## 2. Chuẩn Bị Project

### 2.1 Kiểm tra các file cần thiết

Đảm bảo repo có các file sau (đã được tạo sẵn):

```
coffeeblend_laravel/
├── Dockerfile          ← Cấu hình Docker cho Render
├── .dockerignore       ← Loại bỏ file không cần thiết
└── docker/
    └── start.sh        ← Script khởi động container
```

### 2.2 Kiểm tra .gitignore

Đảm bảo file `.gitignore` đã có (Laravel tạo sẵn):

```
/vendor
/node_modules
.env           ← QUAN TRỌNG: .env phải được ignore!
/public/build  ← Vite build output (sẽ build trong Docker)
```

> ⚠️ **Không bao giờ commit file `.env`** – nó chứa secret keys và mật khẩu DB.

### 2.3 Đảm bảo .env.example đầy đủ

File `.env.example` phải có tất cả các biến mà app cần (không có giá trị thật):

```env
APP_NAME=CoffeeBlend
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-app.onrender.com

DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_CHANNEL=stderr
```

---

## 3. Push Code Lên GitHub

Nếu chưa có GitHub repo:

```bash
# 1. Tạo repo mới trên github.com (đừng tích Add README)

# 2. Trong thư mục project
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/coffeeblend_laravel.git
git push -u origin main
```

Nếu đã có repo rồi, chỉ cần push các file mới (Dockerfile, .dockerignore, docker/start.sh):

```bash
git add Dockerfile .dockerignore docker/start.sh
git commit -m "Add Docker deployment configuration"
git push
```

---

## 4. Tạo Database MySQL Trên Railway

### Bước 4.1 – Đăng ký / Đăng nhập Railway

1. Vào [railway.app](https://railway.app)
2. Đăng nhập bằng **GitHub account**
3. Railway tặng **$5 credit/tháng** cho free tier (đủ cho project nhỏ)

### Bước 4.2 – Tạo MySQL Service

1. Click **"New Project"**
2. Chọn **"Deploy a template"** → tìm **MySQL** → Click **Deploy**
    - HOẶC: Click **"New Project"** → **"Empty Project"** → **"+ Add service"** → **"Database"** → **"Add MySQL"**
3. Railway sẽ tự động tạo MySQL 8 instance

### Bước 4.3 – Mở public networking (BẮT BUỘC)

> ⚠️ **Đây là bước hay bị bỏ qua nhất, dẫn đến lỗi kết nối!**

Railway có **2 loại hostname** – phải dùng đúng loại:

| Loại | Hostname | Port | Dùng được từ |
|------|----------|------|--------------|
| **Internal** (KHÔNG dùng) | `mysql.railway.internal` | `3306` | Chỉ trong Railway |
| **Public** (PHẢI dùng) | `interchange.proxy.rlwy.net` | `11151` (random) | Từ Render và internet |

**Cách tạo public endpoint:**

1. Vào MySQL service → Tab **"Settings"**
2. Section **"Networking"** → Click **"Generate Domain"** hoặc **"Expose Port"**
3. Railway tạo ra: hostname dạng `xxx.proxy.rlwy.net` + port ngẫu nhiên (ví dụ `11151`)

### Bước 4.4 – Lấy thông tin kết nối

Sau khi đã tạo public endpoint:

1. Vào tab **"Connect"** → chọn section **"Public"** (không phải "Private")
2. Ghi lại từ connection string `mysql://USER:PASSWORD@HOST:PORT/DATABASE`:

| Biến Render | Lấy từ Railway | Ví dụ thực tế |
|-------------|----------------|---------------|
| `DB_HOST` | Public hostname | `interchange.proxy.rlwy.net` |
| `DB_PORT` | Public port (**KHÔNG phải 3306**) | `11151` |
| `DB_DATABASE` | Database name | `railway` |
| `DB_USERNAME` | Username | `root` |
| `DB_PASSWORD` | Password (copy toàn bộ, ~32 ký tự) | `elEslcThRFIsnvjnCckoYAyTwQxRStwK` |

> ⚠️ **Password** thường dài ~32 ký tự – click icon **copy** trong Railway để tránh copy thiếu.

---

## 5. Deploy App Lên Render

### Bước 5.1 – Đăng ký / Đăng nhập Render

1. Vào [render.com](https://render.com)
2. Đăng nhập bằng **GitHub account**
3. Render có **free tier** nhưng app sẽ sleep sau 15 phút không có request (paid plan thì không sleep)

### Bước 5.2 – Tạo Web Service

1. Click **"New +"** → chọn **"Web Service"**
2. Chọn tab **"Build and deploy from a Git repository"**
3. Kết nối GitHub → chọn repo `coffeeblend_laravel`
4. Click **"Connect"**

### Bước 5.3 – Cấu hình Web Service

Điền các thông tin sau:

| Field               | Giá trị                                   |
| ------------------- | ----------------------------------------- |
| **Name**            | `coffeeblend-laravel` (hoặc tên tùy chọn) |
| **Runtime**         | **Docker** ← QUAN TRỌNG                   |
| **Branch**          | `main`                                    |
| **Region**          | Singapore (gần VN nhất) hoặc tùy chọn     |
| **Dockerfile Path** | `./Dockerfile` (Render tự detect)         |
| **Instance Type**   | Free (hoặc Starter $7/month nếu cần)      |

> Render sẽ tự detect `Dockerfile` trong repo và dùng nó để build.

### Bước 5.4 – Không click Deploy ngay

Scroll xuống phần **"Environment Variables"** trước khi deploy.

---

## 6. Cấu Hình Biến Môi Trường

Tại trang tạo Web Service trên Render, thêm các biến sau trong phần **"Environment Variables"**:

### Biến bắt buộc

```
APP_NAME=CoffeeBlend
APP_ENV=production
APP_KEY=base64:ts/X3z0yA4JvbgGrTXWSSB9w2Di3uBb++xCNf1phqFU=
APP_DEBUG=false
APP_URL=https://TÊN-APP-CỦA-BẠN.onrender.com
```

> ⚠️ `APP_KEY` – copy từ file `.env` local của bạn (dòng `APP_KEY=base64:...`)
> ⚠️ `APP_URL` – điền URL mà Render sẽ cấp, format: `https://coffeeblend-laravel.onrender.com`

### Biến database (lấy từ Railway – dùng PUBLIC endpoint)

```
DB_CONNECTION=mysql
DB_HOST=interchange.proxy.rlwy.net     ← public hostname từ Railway
DB_PORT=11151                           ← public port (KHÔNG phải 3306!)
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=elEslcThRFIsnvjnCckoYAyTwQxRStwK
```

> ⚠️ Thay các giá trị trên bằng thông tin thật từ Railway của bạn.

### Biến session/cache/queue

```
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_CHANNEL=stderr
```

### Sau khi điền xong

Click **"Create Web Service"** – Render bắt đầu build Docker image và deploy.

Build lần đầu sẽ mất **5-15 phút** (download dependencies, build Vite assets).

---

## 7. Kiểm Tra Sau Deploy

### 7.1 Theo dõi build logs

- Trong dashboard Render → Web Service → Tab **"Logs"**
- Xem container start log, tìm dòng `Starting Apache...`
- Nếu có lỗi migration, sẽ thấy ở đây

### 7.2 Kiểm tra app

1. Mở URL: `https://TÊN-APP.onrender.com`
2. Kiểm tra trang chủ hiển thị đúng
3. Kiểm tra `/up` (health check endpoint của Laravel): `https://TÊN-APP.onrender.com/up`
    - Nếu trả về `{"status":"ok"}` → app hoạt động

### 7.3 Kiểm tra database

```bash
# Chạy lệnh từ Render Shell (Dashboard → Web Service → Shell tab)
php artisan migrate:status
```

---

## 8. Troubleshooting

> Các lỗi dưới đây là lỗi **thực tế đã gặp** khi deploy dự án này lần đầu.

---

### ❌ Lỗi 1: Build Docker thất bại – `Exited with status 1`

**Thông báo:** `==> Exited with status 1`

**Nguyên nhân có thể:**
- File `start.sh` tạo trên Windows có ký tự xuống dòng `\r\n` (CRLF), Linux không đọc được
- `tailwind.config.js` không tồn tại nhưng Dockerfile cố COPY nó
- Lệnh `echo "...\n..."` không sinh newline thật trong PHP ini
- `npm ci` hoặc `npm run build` lỗi

**Đã fix trong Dockerfile:**
- Thêm `RUN sed -i 's/\r$//' /usr/local/bin/start.sh` để strip CRLF
- Bỏ file không tồn tại khỏi lệnh `COPY`
- Đổi `echo` sang `printf` cho PHP ini

---

### ❌ Lỗi 2: `getaddrinfo for mysql.railway.internal failed: Name or service not known`

**Thông báo đầy đủ:**
```
SQLSTATE[HY000] [2002] php_network_getaddresses: getaddrinfo for
mysql.railway.internal failed: Name or service not known
```

**Nguyên nhân:** Đang dùng **internal hostname** của Railway (`mysql.railway.internal`).
Hostname này chỉ hoạt động khi app cũng chạy trong Railway. Render là dịch vụ ngoài, không resolve được.

**Giải quyết:**
1. Vào Railway → MySQL service → Settings → Networking
2. Click **"Generate Domain"** để tạo **public endpoint**
3. Dùng hostname mới (dạng `xxx.proxy.rlwy.net`) thay cho `mysql.railway.internal`

---

### ❌ Lỗi 3: `Connection timed out` (port 3306)

**Thông báo đầy đủ:**
```
SQLSTATE[HY000] [2002] Connection timed out
(Host: mysql-production-f8a6.up.railway.app, Port: 3306)
```

**Nguyên nhân:** Railway **không expose port 3306** ra ngoài. Port 3306 là internal port.
Khi tạo public endpoint, Railway assign một **port ngẫu nhiên khác** (ví dụ: `11151`).

**Giải quyết:**
1. Vào Railway → MySQL → Settings → Networking → xem public endpoint
2. Đọc **port thật** từ connection string (tab "Connect" → "Public")
3. Cập nhật `DB_PORT` trên Render thành port đó (ví dụ `11151`, không phải `3306`)

---

### ❌ Lỗi 4: `Access denied for user 'root'` (sai password)

**Thông báo đầy đủ:**
```
SQLSTATE[HY000] [1045] Access denied for user 'root'@'100.64.0.2'
(using password: YES)
```

**Nguyên nhân:** Password đang gửi đi không khớp với Railway.
- Copy thiếu ký tự (password dài ~32 ký tự)
- Có khoảng trắng thừa khi paste

**Giải quyết:**
1. Vào Railway → MySQL → tab **"Connect"** → section **"Public"**
2. Từ connection string `mysql://root:PASSWORD@host:port/railway`, lấy đúng phần `PASSWORD`
3. Hoặc vào tab **"Variables"** → click icon **copy** cạnh `MYSQLPASSWORD`
4. Cập nhật `DB_PASSWORD` trên Render → Save Changes

---

### ❌ Lỗi 5: App chạy nhưng không có CSS/JS (trang trắng hoặc unstyled)

**Triệu chứng:** Trang web hiển thị nội dung nhưng không có CSS, layout bị vỡ.
`/up` endpoint vẫn trả về OK.

**Nguyên nhân:** **Mixed Content** – Laravel tạo asset URL với `http://` nhưng browser đang ở `https://`.

Cụ thể: Render dùng reverse proxy – HTTPS được terminate tại proxy, request vào container là HTTP.
Laravel không biết đang ở HTTPS → `asset()` sinh URL `http://...` → browser block.

**Đã fix trong `bootstrap/app.php`:**
```php
->withMiddleware(function (Middleware $middleware): void {
    // Trust Render's reverse proxy để Laravel nhận diện đúng HTTPS
    $middleware->trustProxies(at: '*');
    // ...
})
```

Sau khi push fix này, Render auto-deploy và CSS sẽ load bình thường.

---

### ❌ Lỗi 6: `No application encryption key has been specified`

**Nguyên nhân:** Thiếu `APP_KEY` trong environment variables trên Render.

**Giải quyết:** Lấy giá trị từ file `.env` local (dòng `APP_KEY=base64:...`) → thêm vào Render env vars.

---

### ❌ Lỗi 7: `The stream or file storage/logs/laravel.log could not be opened`

**Nguyên nhân:** Thư mục `storage/logs/` không có quyền ghi (hoặc filesystem ephemeral).

**Giải quyết:** Set `LOG_CHANNEL=stderr` trong Render env vars – log ghi ra console thay vì file.

---

### ⚠️ App chạy nhưng bị sleep / chậm

**Nguyên nhân:** Free tier của Render sleep sau **15 phút** không có request. Lần đầu truy cập sau khi sleep mất ~30 giây.

**Giải quyết tạm thời – Dùng UptimeRobot để ping định kỳ:**

1. Đăng ký miễn phí tại [uptimerobot.com](https://uptimerobot.com) → xác nhận email
2. Dashboard → **"+ Add New Monitor"**
3. Điền thông tin:

| Field | Giá trị |
|-------|---------|
| Monitor Type | `HTTP(s)` |
| Friendly Name | `CoffeeBlend Laravel` |
| URL | `https://TÊN-APP.onrender.com/up` |
| Monitoring Interval | `5 minutes` |

4. (Tuỳ chọn) Phần **"Alert Contacts"** → thêm email để nhận thông báo khi app down
5. Click **"Create Monitor"**

> **Dùng `/up` thay vì `/`** – `/up` là health check endpoint có sẵn của Laravel 11 (định nghĩa trong `bootstrap/app.php`), không query DB, không render Blade, nhanh hơn và ít tốn tài nguyên hơn nhiều so với ping trang chủ.

**Giải quyết triệt để:** Nâng lên **Starter plan** ($7/month) – không bao giờ sleep, không bị giới hạn instance hours.

---

### ⚠️ Vượt quá 750 Free Instance Hours – App bị suspend

**Triệu chứng:** Render thông báo `Free Instance Hours` gần đạt hoặc đã đạt `750 hours/month`, app bị dừng hoàn toàn.

**Nguyên nhân – Nghịch lý UptimeRobot:**

Render tính giờ theo thời gian app **đang chạy**, không phải theo lượt truy cập thật. Giới hạn 750 giờ/tháng được chia chung cho **tất cả** Web Service free trong cùng một account.

Nếu có **3 app** đều dùng UptimeRobot ping mỗi 5 phút → cả 3 chạy 24/7:

```
3 app × 24 giờ × 30 ngày = 2,160 giờ cần
                            750 giờ có
→ Hết hạn sau ~10 ngày → Render suspend tất cả
```

Nếu **không** dùng UptimeRobot → app tự ngủ khi không có người dùng thật → 750 giờ đủ dùng cả tháng.

> ⚠️ UptimeRobot giữ app tỉnh nhưng đồng thời tiêu hết instance hours sớm hơn nhiều so với để app tự ngủ. Khi account hết 750 giờ, **tất cả** app free bị suspend – còn tệ hơn sleep.

**Giải pháp tuỳ tình huống:**

| Tình huống | Giải pháp |
|------------|-----------|
| Có 1 app quan trọng, các app còn lại là demo | Chỉ giữ UptimeRobot cho **1 app** duy nhất (1 × 24h × 30 = 720h < 750h ✅), pause monitor của các app còn lại |
| Tất cả đều ít người dùng | Tắt UptimeRobot cho tất cả, chấp nhận delay ~30 giây khi có người vào |
| Cần 1 app luôn sẵn sàng | Nâng app đó lên **Starter ($7/month)** – không tính vào 750h free, không bao giờ sleep |

**Cách pause monitor trên UptimeRobot:** Dashboard → chọn monitor → click **"Pause"** (có thể bật lại bất cứ lúc nào).

---

## 9. Re-Deploy Khi Update Code

Render tự động deploy mỗi khi push code lên branch `main`:

```bash
# Sau khi sửa code
git add .
git commit -m "Update features"
git push origin main

# → Render tự detect và build lại
# → Theo dõi tại Render Dashboard → Events tab
```

### Manual deploy (khi cần)

Vào Render Dashboard → Web Service → Click **"Manual Deploy"** → **"Deploy latest commit"**

---

## 10. Lưu Ý Quan Trọng

### File system là ephemeral (tạm thời)

Render container **không lưu file** khi restart. Nghĩa là:

- ❌ User upload ảnh → mất khi app restart
- ✅ Data trong MySQL Railway → được lưu vĩnh viễn

**Giải pháp nếu cần lưu file upload:**

- Dùng **Cloudinary** (miễn phí 25GB) hoặc **AWS S3** cho file storage
- Hoặc dùng Render **Persistent Disk** (có phí)

### Bảo mật

- `APP_DEBUG=false` trong production – đã set rồi
- Không commit `.env` – đã có trong `.gitignore`
- Đổi mật khẩu Railway DB định kỳ
- Dùng HTTPS – Render tự cấp SSL certificate miễn phí

### Chi phí ước tính

| Dịch vụ            | Free Tier                | Paid               |
| ------------------ | ------------------------ | ------------------ |
| Render Web Service | Có (sleep sau 15 phút)   | $7/month (Starter) |
| Railway MySQL      | $5 credit/month          | Theo usage         |
| Domain custom      | Miễn phí khi dùng Render | ~$10/year          |

### Seeder và dữ liệu mẫu

Nếu cần chạy seeder sau khi deploy:

```bash
# Từ Render Shell
php artisan db:seed --class=AdminSeeder
```

Khi deploy lên Railway, chạy theo thứ tự:

```bash
php artisan migrate
php artisan db:seed
```

Hoặc gộp lại:

```bash
php artisan migrate --seed
```

---

## Tóm Tắt Nhanh (Checklist)

- [ ] Push code (bao gồm `Dockerfile`, `.dockerignore`, `docker/start.sh`) lên GitHub
- [ ] Tạo MySQL service trên Railway, lấy credentials
- [ ] Expose Railway MySQL port public
- [ ] Tạo Web Service trên Render, chọn Docker runtime
- [ ] Thêm tất cả env vars (đặc biệt `APP_KEY`, `DB_*`)
- [ ] Chờ build xong (~10 phút), kiểm tra logs
- [ ] Truy cập URL, test `/up` endpoint
- [ ] Chạy seeder nếu cần

---

_Viết bởi Claude Code · CoffeeBlend Laravel Project_
