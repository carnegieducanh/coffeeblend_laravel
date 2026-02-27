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

### Bước 4.3 – Lấy thông tin kết nối

1. Click vào service **MySQL** vừa tạo
2. Chọn tab **"Variables"**
3. Ghi lại các giá trị sau:

| Variable | Ví dụ giá trị |
|----------|----------------|
| `MYSQLHOST` | `containers-us-west-xxx.railway.app` |
| `MYSQLPORT` | `6543` (khác 3306!) |
| `MYSQLDATABASE` | `railway` |
| `MYSQLUSER` | `root` |
| `MYSQLPASSWORD` | `AbCdEfGhIjKl` |

> 💡 Hoặc chuyển sang tab **"Connect"** → chọn **"MySQL"** → copy từng field riêng lẻ.

### Bước 4.4 – Mở port cho kết nối ngoài

1. Trong MySQL service → Tab **"Settings"**
2. Section **"Networking"** → Click **"Generate Domain"** hoặc **"Expose Port"**
3. Đây là bước cần thiết để Render kết nối được vào Railway DB

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

| Field | Giá trị |
|-------|---------|
| **Name** | `coffeeblend-laravel` (hoặc tên tùy chọn) |
| **Region** | Singapore (gần VN nhất) hoặc tùy chọn |
| **Branch** | `main` |
| **Runtime** | **Docker** ← QUAN TRỌNG |
| **Dockerfile Path** | `./Dockerfile` (Render tự detect) |
| **Instance Type** | Free (hoặc Starter $7/month nếu cần) |

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

### Biến database (lấy từ Railway ở bước 4.3)

```
DB_CONNECTION=mysql
DB_HOST=containers-us-west-xxx.railway.app
DB_PORT=6543
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=AbCdEfGhIjKl
```

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

### Lỗi: `No application encryption key has been specified`

**Nguyên nhân:** Thiếu `APP_KEY` trong environment variables.
**Giải quyết:** Thêm `APP_KEY` vào Render env vars (lấy từ `.env` local).

---

### Lỗi: `SQLSTATE[HY000] [2002] Connection refused` hoặc `php_network_getaddresses`

**Nguyên nhân:** Sai thông tin kết nối DB hoặc Railway chưa expose port.
**Giải quyết:**
1. Kiểm tra lại `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD`
2. Đảm bảo Railway MySQL đã expose port public (bước 4.4)
3. Thử kết nối từ local: `mysql -h HOST -P PORT -u USER -pPASSWORD`

---

### Lỗi: `chmod: changing permissions of '/var/www/html/...' denied`

**Nguyên nhân:** Quyền file trong Docker.
**Giải quyết:** Đã được xử lý trong Dockerfile. Nếu vẫn lỗi, kiểm tra `docker/start.sh`.

---

### Lỗi: CSS/JS không load (404)

**Nguyên nhân:** Vite build chưa chạy hoặc `public/build/` không có.
**Giải quyết:** Kiểm tra Docker build logs xem `npm run build` có thành công không.

---

### Lỗi: `The stream or file storage/logs/laravel.log could not be opened`

**Nguyên nhân:** Thư mục storage không có quyền ghi.
**Giải quyết:** Đã set `LOG_CHANNEL=stderr` trong env vars – log sẽ ghi ra console thay vì file.

---

### App chạy nhưng chậm / bị sleep

**Nguyên nhân:** Free tier của Render sleep sau 15 phút không có request.
**Giải quyết:**
- Dùng dịch vụ ping định kỳ (UptimeRobot free) để giữ app tỉnh
- Hoặc nâng lên **Starter plan** ($7/month)

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

| Dịch vụ | Free Tier | Paid |
|---------|-----------|------|
| Render Web Service | Có (sleep sau 15 phút) | $7/month (Starter) |
| Railway MySQL | $5 credit/month | Theo usage |
| Domain custom | Miễn phí khi dùng Render | ~$10/year |

### Seeder và dữ liệu mẫu

Nếu cần chạy seeder sau khi deploy:

```bash
# Từ Render Shell
php artisan db:seed --class=AdminSeeder
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

*Viết bởi Claude Code · CoffeeBlend Laravel Project*
