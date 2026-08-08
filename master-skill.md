# EMS-smb — Master Skill (Bản dịch mã nguồn → Go)

> Tài liệu này **không mô tả Laravel**. Nó chứa **mã nguồn + logic nghiệp vụ** hiện đang chạy.
> Người đọc (hoặc AI) đọc code trong đây → biên dịch lại thành **hàm Go** + **DB PostgreSQL** + **frontend Next.js**.
> Cấu trúc được tổ chức theo **module nghiệp vụ**, không theo framework, để dễ đọc và dễ sửa về sau.

---

## MỤC LỤC
1. [Cách dùng tài liệu này](#1-cách-dùng-tài-liệu-này)
2. [Kiến trúc hệ thống](#2-kiến-trúc-hệ-thống)
3. [Tổ chức code Go — đề xuất](#3-tổ-chức-code-go--đề-xuất)
4. [Cơ sở dữ liệu — PostgreSQL](#4-cơ-sở-dữ-liệu--postgresql)
5. [Mã nguồn & logic nghiệp vụ (để dịch sang Go)](#5-mã-nguồn--logic-nghiệp-vụ-để-dịch-sang-go)
6. [Thêm dữ liệu vào DB (chuẩn seed)](#6-thêm-dữ-liệu-vào-db-chuẩn-seed)
7. [UI / Design system (giữ nguyên)](#7-ui--design-system-giữ-nguyên)
8. [API contract](#8-api-contract)

---

## 1. CÁCH DÙNG TÀI LIỆU NÀY

- **Phần 5** là trái tim của tài liệu: chứa **code nguồn gốc** (đã rút gọn comment, giữ nguyên logic). Đọc nó → viết hàm Go tương đương theo tổ chức ở phần 3.
- Mỗi khối code trong phần 5 kèm chú thích `→ Go: <tên package>.<tên function>` để biết đích đến.
- **Phần 4** định nghĩa DB PostgreSQL — là hợp đồng dữ liệu bắt buộc.
- **Phần 7** là hợp đồng giao diện — frontend Next.js giữ nguyên class/token nên UI không đổi.
- Môi trường hiện tại (để vận hành hệ cũ trong lúc viết lại): xem phụ lục cuối.

---

## 2. KIẾN TRÚC HỆ THỐNG

### 2.1 Mô hình dữ liệu (2 nhóm)
| Nhóm | Phạm vi | Ví dụ |
|---|---|---|
| **Global** | Công ty SMB+, không thuộc event nào | settings `event_id = NULL`, posts global, company_profile_items |
| **Event** | Thuộc 1 event cụ thể | speakers, schedules, venues, hotels, galleries, sponsors, faqs, amenities, prices, settings `event_id = X` |

- Mọi bảng event-scoped đều có cột `event_id` (BIGINT NULL). Riêng `posts` **không có** `event_id` (global).
- Event **hiển thị công khai** = event có `is_active = true`. Chỉ 1 event active tại 1 thời điểm.
- **Settings merge rule**: khi render 1 event → `map = global_settings ∪ event_settings` (event đè global).
- **Contact**: `event_id = 0` → gửi từ **Home Page**; `event_id = X` → gửi từ trang event X; `NULL` → legacy.
- **Auth**: 2 role (`Admin` id=1, `User` id=2). Quyền chi tiết lưu bảng `permissions` + `permission_role`. **Admin** là role duy nhất được sửa hồ sơ công ty.
- **Admin context**: khi quản lý trong admin, có khái niệm "event đang chọn" (`current_event_id` từ session). Các CRUD content event lọc theo event này; settings index hiển thị global + event đang chọn.
- **Hai "tầng" API**: admin web (`/admin/*`, session) + API v1 (`/api/v1/*`, Passport Bearer). API v1 **không lọc theo event** — khác admin web (xem 5.10).
- **Middleware hệ thống**: `SetLocale`, `AuthGates` (load permissions, cache 24h), `auth`, `throttle:60,1` (API), exception JSON cho request `api/*` hoặc `Accept: application/json`.

### 2.2 Các thành phần hệ thống mới (target)
- **API**: Go (REST + JWT).
- **Frontend**: Next.js (SSR/ISR, giữ nguyên design system).
- **DB**: PostgreSQL 16 (thay MySQL).
- **Cache/Session**: Redis 7.
- **Triển khai**: Docker Compose (db + redis + api + web).

---

## 3. TỔ CHỨC CODE GO — GIỐNG LARAVEL (thư mục + file)

> Mục đích: người quen Laravel nhìn vào là hiểu. Cấu trúc **theo module nghiệp vụ**, mỗi module tự quản schema con. Tên file/khái niệm ánh xạ thẳng với Laravel đang chạy.

```
backend/
├── cmd/api/main.go                  # ~ public/index.php: nạp config, DB, router, server
├── internal/
│   ├── config/config.go             # ~ config/*.php: đọc .env (POSTGRES_URL, REDIS, JWT_SECRET, PORT)
│   ├── database/
│   │   ├── db.go                    # ~ database/connection: mở pool PG (pgx)
│   │   ├── migrate.go               # ~ artisan migrate: chạy migration (chi tiết 3.1)
│   │   ├── seed.go                  # ~ artisan db:seed: gọi Seed<Name> theo thứ tự (phần 6)
│   │   └── migrations_table.go      # bảng migrations + đánh dấu đã chạy
│   ├── http/
│   │   ├── router.go                # ~ routes/web.php + routes/api.php
│   │   ├── middleware/
│   │   │   ├── auth.go              # ~ middleware 'auth' (JWT)
│   │   │   ├── admin_only.go        # ~ CompanyProfileController::authorizeAdmin
│   │   │   ├── current_event.go     # ~ helper current_event_id() + session
│   │   │   └── cors.go
│   │   └── response.go              # helper JSON/text response
│   ├── auth/                        # ~ app/Http/Controllers/Auth/*
│   │   ├── handler.go               # login/logout/password reset
│   │   ├── service.go               # verify bcrypt, tạo JWT
│   │   └── role_permission.go       # ~ Gate: load permissions, check '<res>_<action>'
│   ├── company/                     # ~ Admin\CompanyProfileController + CompanyProfileItemsController + HomeController@index
│   │   ├── handler.go               # GET/PUT /admin/company-profile
│   │   ├── item_handler.go          # CRUD + up/down + media items
│   │   └── home_handler.go          # GET / (trả settings + posts + profileItems)
│   ├── event/                       # ~ EventController + Admin\EventsController + các CRUD content
│   │   ├── handler.go               # GET /event, GET /event/{slug|id}, GET /speaker/{id}
│   │   ├── admin_handler.go         # CRUD events + POST /admin/events/switch
│   │   ├── service.go               # loadSettings (merge global+event), loadActiveEvent, groupByDay
│   │   └── content_handler.go       # CRUD: speakers/schedules/venues/hotels/galleries/sponsors/faqs/amenities/prices
│   ├── contact/                     # ~ HomeController@storeContact + Admin\ContactMessagesController
│   │   ├── handler.go               # POST /contact, GET/DELETE /admin/contacts*
│   │   └── service.go               # source_label, filter by event_id
│   ├── post/                        # ~ Admin\PostsController + Post model
│   │   └── handler.go               # CRUD posts, lọc is_published
│   ├── media/                       # ~ Traits/MediaUploadingTrait + media table
│   │   ├── store.go                 # nhận file → tmp/uploads → trả {name, original_name}
│   │   └── attach.go                # ghi media table, conversions (thumb/card)
│   ├── settings/                    # ~ Setting model + Admin\SettingsController
│   │   ├── handler.go               # CRUD settings
│   │   └── service.go               # LoadSettings(global+event) dùng chung
│   └── user/                        # ~ Admin\UsersController/RolesController/PermissionsController
│       └── handler.go               # CRUD users/roles/permissions + sync quan hệ
├── migrations/                      # ~ database/migrations (chi tiết 3.1)
│   ├── 001_create_users_table.up.sql
│   ├── 001_create_users_table.down.sql
│   └── ...                          # mỗi file 1 thay đổi schema, có up + down
├── seeders/                         # ~ database/seeders (chi tiết 6.2)
├── storage/
│   ├── tmp/uploads/                 # file tạm Dropzone
│   └── media/                       # file media chính thức
├── pkg/                             # dùng chung: slugify, paginate, validator
│   ├── slugify/slugify.go
│   └── validator/validator.go
├── .env.example
├── go.mod
└── Dockerfile
```

Ánh xạ Laravel → Go:
| Laravel | Go |
|---|---|
| `routes/web.php` + `routes/api.php` | `internal/http/router.go` |
| `app/Http/Controllers/*` | `internal/<module>/handler.go` |
| `app/Models/*` | `internal/<module>/*.go` (struct) |
| `database/migrations/*` | `migrations/*.up.sql` + `*.down.sql` |
| `database/seeders/*` | `seeders/*` (Go function `Seed<Name>`) |
| `artisan migrate` | `cmd migrate` (chạy `internal/database/migrate.go`) |
| `artisan db:seed` | `cmd seed` (chạy `internal/database/seed.go`) |
| Middleware `auth`, Gate | `internal/http/middleware/*`, `internal/auth/role_permission.go` |

Quy tắc tổ chức:
1. **1 module = 1 thư mục**, chứa handler + service + model riêng.
2. Handler chỉ đọc request → gọi service → trả response; không viết SQL.
3. Service chứa toàn bộ logic nghiệp vụ (đọc/ghi DB).
4. Model struct trong mỗi module, tên khớp bảng (phần 4).
5. Middleware dùng chung: `auth` (JWT), `admin_only` (role Admin), `current_event` (session/admin context).

---

### 3.1 HỆ THỐNG MIGRATION GIỐNG LARAVEL (tạo cột / sửa bảng KHÔNG mất data)

> Giống `php artisan make:migration` + `php artisan migrate`. Mỗi file migration = 1 thay đổi schema (tạo bảng, **thêm cột**, đổi kiểu, index...), có `up` (áp dụng) và `down` (hoàn tác). Hệ thống ghi log vào bảng `schema_migrations` để chỉ chạy 1 lần, chạy được nhiều lần (idempotent), **tuyệt đối không xóa data**.

#### 3.1.1 Cách tạo migration
```bash
# 1. Tạo cặp file SQL mới (đánh số tăng dần, mô tả rõ ràng)
migrations/015_add_speaker_title_to_speakers_table.up.sql
migrations/015_add_speaker_title_to_speakers_table.down.sql
```
> Quy ước đặt tên: `<stt 3 số>_<động từ>_<đối tượng>_<bảng>_table.(up|down).sql`
> - `create_xxx_table` → tạo bảng
> - `add_xxx_to_<table>_table` → thêm cột
> - `modify_xxx_column_in_<table>_table` → sửa cột
> - `drop_xxx_from_<table>_table` → xóa cột
> - `create_<join>_pivot_table` → bảng trung gian

#### 3.1.2 Nội dung file — TẠO BẢNG (up)
```sql
-- 001_create_users_table.up.sql
CREATE TABLE IF NOT EXISTS users (
  id                BIGSERIAL PRIMARY KEY,
  name              VARCHAR(255) NOT NULL,
  email             VARCHAR(255) NOT NULL UNIQUE,
  password          VARCHAR(255) NOT NULL,
  remember_token    VARCHAR(100) NULL,
  created_at        TIMESTAMPTZ NULL DEFAULT now(),
  updated_at        TIMESTAMPTZ NULL DEFAULT now(),
  deleted_at        TIMESTAMPTZ NULL
);
```
File down:
```sql
-- 001_create_users_table.down.sql
DROP TABLE IF EXISTS users;
```
> Luôn dùng `IF NOT EXISTS` (up) và `IF EXISTS` (down) → chạy lại an toàn, không lỗi khi bảng đã tồn tại.

#### 3.1.3 Nội dung file — THÊM CỘT MỚI (up) — KHÔNG MẤT DATA
```sql
-- 015_add_speaker_title_to_speakers_table.up.sql
-- Bước 1: thêm cột dạng NULL (không ràng buộc) → bảng có thêm cột, data cũ vẫn nguyên
ALTER TABLE speakers ADD COLUMN IF NOT EXISTS title VARCHAR(255) NULL;

-- Bước 2 (nếu cần default cho data cũ): cập nhật giá trị cho các dòng cũ
-- UPDATE speakers SET title = 'Speaker' WHERE title IS NULL;

-- Bước 3: nếu muốn NOT NULL có default → thêm default rồi mới set NOT NULL
-- ALTER TABLE speakers ALTER COLUMN title SET DEFAULT '';
-- UPDATE speakers SET title = '' WHERE title IS NULL;
-- ALTER TABLE speakers ALTER COLUMN title SET NOT NULL;
```
File down (hoàn tác — xóa cột, data cột đó bị mất, các cột khác nguyên vẹn):
```sql
ALTER TABLE speakers DROP COLUMN IF EXISTS title;
```

#### 3.1.4 Nội dung file — SỬA / ĐỔI KIỂU CỘT (up)
```sql
-- Thay đổi kiểu: đổi VARCHAR → TEXT (không mất data)
ALTER TABLE settings ALTER COLUMN value TYPE TEXT USING value::TEXT;

-- Đổi tên cột (giữ nguyên data)
ALTER TABLE hotels RENAME COLUMN desc TO description;

-- Thêm index cho cột có sẵn
CREATE INDEX IF NOT EXISTS settings_event_idx ON settings(event_id);

-- Thêm ràng buộc FK
ALTER TABLE schedules
  ADD CONSTRAINT schedules_speaker_id_fk
  FOREIGN KEY (speaker_id) REFERENCES speakers(id) ON DELETE SET NULL;
```
> Chú ý an toàn: kiểm tra trước khi thay đổi — dùng transaction; nếu kiểu cũ chứa giá trị không cast được sang kiểu mới thì phải xử lý data trước (UPDATE) rồi mới `ALTER`.

#### 3.1.5 Bảng `schema_migrations` (ghi log đã chạy)
```sql
-- Tạo tự động khi khởi tạo hệ thống (bằng code Go, KHÔNG phải file migration)
CREATE TABLE IF NOT EXISTS schema_migrations (
  version    VARCHAR(255) PRIMARY KEY,   -- tên file: '015_add_speaker_title_to_speakers_table'
  applied_at TIMESTAMPTZ NOT NULL DEFAULT now()
);
```

#### 3.1.6 Bộ chạy migration — `internal/database/migrate.go`
```go
package database

// Bước thực hiện mỗi lần gọi: `cmd migrate`
// 1. Đọc toàn bộ file migrations/*.up.sql, sắp xếp theo số thứ tự (prefix 3 số).
// 2. Đọc danh sách version đã chạy từ bảng schema_migrations.
// 3. Với mỗi file CHƯA chạy:
//    a. BẮT ĐẦU TRANSACTION.
//    b. Chạy nội dung SQL (up).
//    c. INSERT version vào schema_migrations.
//    d. COMMIT. Nếu lỗi → ROLLBACK (file đó không được đánh dấu, chạy lại sau khi sửa).
// 4. File nào rồi thì bỏ qua → chạy lại lệnh migrate nhiều lần vẫn an toàn.
// 5. `cmd migrate:down` = chạy file *.down.sql của version gần nhất (hoặc theo tên).
//
// Idempotent: mọi câu SQL trong up đều có IF NOT EXISTS / IF EXISTS
// → kể cả khi file bị sửa giữa chừng, chạy lại không gây lỗi hay mất data.
```

#### 3.1.7 Quy tắc VÀNG để KHÔNG mất data (áp dụng mọi thay đổi)
1. **Chỉ thêm/đổi schema, không đụng data**: không bao giờ xóa bảng trong `up`; `DROP` chỉ nằm trong `down`.
2. **Thêm cột luôn bắt đầu bằng `ADD COLUMN IF NOT EXISTS ... NULL`** (không NOT NULL ngay khi bảng có data). Muốn NOT NULL thì: thêm NULL → `UPDATE` default cho dòng cũ → `SET NOT NULL` (hoặc `SET DEFAULT` trước).
3. **Mỗi migration gói trong 1 transaction** → lỗi nửa chừng sẽ rollback, schema và data giữ nguyên.
4. **`down` phải khôi phục được**: `DROP COLUMN IF EXISTS`, `DROP TABLE IF EXISTS`, `DROP INDEX IF EXISTS`.
5. **Test trên bản sao** trước khi chạy thật: chạy migration trên DB test có data mẫu, kiểm tra không lỗi và số dòng không đổi.
6. **Không sửa file migration đã chạy** (đã có trong schema_migrations). Muốn đổi → tạo file migration mới.
7. Với data phải tính toán lại (ví dụ thêm cột có giá trị suy ra) → viết `UPDATE` trong cùng transaction ngay sau `ADD COLUMN`.

#### 3.1.8 Ánh xạ từ 30 migration Laravel → thư mục `migrations/`
Các bảng hiện có (tạo từ schema mục 4):
```
001 users        002 roles        003 permissions   004 role_user   005 permission_role
006 settings     007 events       008 speakers      009 schedules   010 venues
011 hotels       012 galleries    013 sponsors      014 faqs        015 amenities
016 prices       017 amenity_price 018 posts        019 media       020 password_reset_tokens
021 company_profile_items 022 contact_messages
```
> Khi cần thay đổi (ví dụ thêm cột `title` vào speakers) → tạo `023_add_..._table.up.sql` + `.down.sql`, KHÔNG sửa file 008.

---

## 4. CƠ SỞ DỮ LIỆU — POSTGRESQL

> Dịch 1:1 từ 30 migrations hiện có (25 bảng nghiệp vụ + 5 bảng Passport `oauth_*`). Kiểu dữ liệu Postgres. ID tự tăng dùng `BIGSERIAL`. Soft delete = cột `deleted_at TIMESTAMPTZ NULL`.

### 4.1 users & auth
```sql
CREATE TABLE users (
  id                BIGSERIAL PRIMARY KEY,
  name              VARCHAR(255) NOT NULL,
  email             VARCHAR(255) NOT NULL,
  email_verified_at TIMESTAMPTZ NULL,
  password          VARCHAR(255) NOT NULL,          -- bcrypt hash
  remember_token    VARCHAR(100) NULL,
  created_at        TIMESTAMPTZ NULL DEFAULT now(),
  updated_at        TIMESTAMPTZ NULL DEFAULT now(),
  deleted_at        TIMESTAMPTZ NULL
);

CREATE TABLE roles (
  id         BIGSERIAL PRIMARY KEY,
  title      VARCHAR(255) NULL,
  created_at TIMESTAMPTZ NULL DEFAULT now(),
  updated_at TIMESTAMPTZ NULL DEFAULT now(),
  deleted_at TIMESTAMPTZ NULL
);

CREATE TABLE permissions (
  id         BIGSERIAL PRIMARY KEY,
  title      VARCHAR(255) NULL,
  created_at TIMESTAMPTZ NULL DEFAULT now(),
  updated_at TIMESTAMPTZ NULL DEFAULT now(),
  deleted_at TIMESTAMPTZ NULL
);

CREATE TABLE role_user (
  user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  role_id BIGINT NOT NULL REFERENCES roles(id) ON DELETE CASCADE
);

CREATE TABLE permission_role (
  role_id       BIGINT NOT NULL REFERENCES roles(id) ON DELETE CASCADE,
  permission_id BIGINT NOT NULL REFERENCES permissions(id) ON DELETE CASCADE
);

CREATE TABLE password_reset_tokens (
  email      VARCHAR(255) PRIMARY KEY,
  token      VARCHAR(255) NOT NULL,
  created_at TIMESTAMPTZ NULL
);
```

### 4.2 media (1 bảng dùng chung, polymorphic)
```sql
CREATE TABLE media (
  id                     BIGSERIAL PRIMARY KEY,
  model_type             VARCHAR(255) NOT NULL,      -- tên model: "App\\Speaker", "company_profile_item"...
  model_id               BIGINT NOT NULL,
  uuid                   VARCHAR(255) NULL UNIQUE,
  collection_name        VARCHAR(255) NOT NULL,      -- photo/photos/logo/cover/image
  name                   VARCHAR(255) NOT NULL,
  file_name              VARCHAR(255) NOT NULL,
  mime_type              VARCHAR(255) NULL,
  disk                   VARCHAR(255) NOT NULL,
  conversions_disk       VARCHAR(255) NULL,
  size                   BIGINT NOT NULL,
  manipulations          JSONB NOT NULL DEFAULT '{}',
  custom_properties      JSONB NOT NULL DEFAULT '{}',
  generated_conversions  JSONB NOT NULL DEFAULT '{}',
  responsive_images      JSONB NOT NULL DEFAULT '{}',
  order_column           INTEGER NULL,
  created_at             TIMESTAMPTZ NULL DEFAULT now(),
  updated_at             TIMESTAMPTZ NULL DEFAULT now(),
  INDEX (model_type, model_id)
);
```
> Ứng với từng model: **conversions** `thumb` (50x50 hoặc 80x80); Post còn `card` (640x480).

### 4.3 settings
```sql
CREATE TABLE settings (
  id         BIGSERIAL PRIMARY KEY,
  key        VARCHAR(255) NOT NULL,
  value      TEXT NULL,
  event_id   BIGINT NULL,             -- KHÔNG có FK ở source; thêm FK là tùy chọn
  created_at TIMESTAMPTZ NULL DEFAULT now(),
  updated_at TIMESTAMPTZ NULL DEFAULT now(),
  deleted_at TIMESTAMPTZ NULL
);
CREATE INDEX settings_event_idx ON settings(event_id);
```
> `event_id NULL` = global (home/footer/contact/company profile). `event_id = X` = settings riêng event X.
> **Lưu ý (đúng source)**: cột `event_id` được thêm qua 1 migration gộp `add_event_id_to_tables` cho 10 bảng (speakers, schedules, venues, hotels, galleries, sponsors, faqs, amenities, prices, settings) — dạng `unsignedInteger NULL` **không có FK**. Trong PostgreSQL mới nên giữ `BIGINT NULL`; có thêm FK/`ON DELETE CASCADE` là quyết định riêng.

### 4.4 events & content
```sql
CREATE TABLE events (
  id         BIGSERIAL PRIMARY KEY,
  name       VARCHAR(255) NOT NULL,
  slug       VARCHAR(255) NULL UNIQUE,
  description TEXT NULL,
  start_date VARCHAR(255) NULL,
  end_date   VARCHAR(255) NULL,
  is_active  BOOLEAN NOT NULL DEFAULT true,
  created_at TIMESTAMPTZ NULL DEFAULT now(),
  updated_at TIMESTAMPTZ NULL DEFAULT now(),
  deleted_at TIMESTAMPTZ NULL
);

CREATE TABLE speakers (
  id               BIGSERIAL PRIMARY KEY,
  name             VARCHAR(255) NOT NULL,
  description      TEXT NULL,
  twitter          VARCHAR(255) NULL,
  facebook         VARCHAR(255) NULL,
  linkedin         VARCHAR(255) NULL,
  full_description TEXT NULL,
  event_id         BIGINT NULL REFERENCES events(id) ON DELETE CASCADE,
  created_at       TIMESTAMPTZ NULL DEFAULT now(),
  updated_at       TIMESTAMPTZ NULL DEFAULT now(),
  deleted_at       TIMESTAMPTZ NULL
);  -- media collection: photo (conversion thumb 50x50)

CREATE TABLE schedules (
  id         BIGSERIAL PRIMARY KEY,
  day_number INTEGER NOT NULL,
  start_time TIME NOT NULL,
  title      VARCHAR(255) NOT NULL,
  subtitle   VARCHAR(255) NULL,
  speaker_id BIGINT NULL REFERENCES speakers(id) ON DELETE SET NULL,
  event_id   BIGINT NULL REFERENCES events(id) ON DELETE CASCADE,
  created_at TIMESTAMPTZ NULL DEFAULT now(),
  updated_at TIMESTAMPTZ NULL DEFAULT now(),
  deleted_at TIMESTAMPTZ NULL
);

CREATE TABLE venues (
  id          BIGSERIAL PRIMARY KEY,
  name        VARCHAR(255) NOT NULL,
  address     VARCHAR(255) NOT NULL,
  latitude    VARCHAR(255) NOT NULL,
  longitude   VARCHAR(255) NOT NULL,
  description TEXT NULL,
  event_id    BIGINT NULL REFERENCES events(id) ON DELETE CASCADE,
  created_at  TIMESTAMPTZ NULL DEFAULT now(),
  updated_at  TIMESTAMPTZ NULL DEFAULT now(),
  deleted_at  TIMESTAMPTZ NULL
);  -- media collection: photos

CREATE TABLE hotels (
  id          BIGSERIAL PRIMARY KEY,
  name        VARCHAR(255) NOT NULL,
  address     VARCHAR(255) NULL,
  description TEXT NULL,
  rating      INTEGER NULL,
  event_id    BIGINT NULL REFERENCES events(id) ON DELETE CASCADE,
  created_at  TIMESTAMPTZ NULL DEFAULT now(),
  updated_at  TIMESTAMPTZ NULL DEFAULT now(),
  deleted_at  TIMESTAMPTZ NULL
);  -- media collection: photo

CREATE TABLE galleries (
  id         BIGSERIAL PRIMARY KEY,
  name       VARCHAR(255) NOT NULL,
  event_id   BIGINT NULL REFERENCES events(id) ON DELETE CASCADE,
  created_at TIMESTAMPTZ NULL DEFAULT now(),
  updated_at TIMESTAMPTZ NULL DEFAULT now(),
  deleted_at TIMESTAMPTZ NULL
);  -- media collection: photos

CREATE TABLE sponsors (
  id         BIGSERIAL PRIMARY KEY,
  name       VARCHAR(255) NOT NULL,
  link       VARCHAR(255) NULL,
  event_id   BIGINT NULL REFERENCES events(id) ON DELETE CASCADE,
  created_at TIMESTAMPTZ NULL DEFAULT now(),
  updated_at TIMESTAMPTZ NULL DEFAULT now(),
  deleted_at TIMESTAMPTZ NULL
);  -- media collection: logo

CREATE TABLE faqs (
  id         BIGSERIAL PRIMARY KEY,
  question   VARCHAR(255) NOT NULL,
  answer     VARCHAR(255) NOT NULL,
  event_id   BIGINT NULL REFERENCES events(id) ON DELETE CASCADE,
  created_at TIMESTAMPTZ NULL DEFAULT now(),
  updated_at TIMESTAMPTZ NULL DEFAULT now(),
  deleted_at TIMESTAMPTZ NULL
);

CREATE TABLE amenities (
  id         BIGSERIAL PRIMARY KEY,
  name       VARCHAR(255) NOT NULL,
  event_id   BIGINT NULL REFERENCES events(id) ON DELETE CASCADE,
  created_at TIMESTAMPTZ NULL DEFAULT now(),
  updated_at TIMESTAMPTZ NULL DEFAULT now(),
  deleted_at TIMESTAMPTZ NULL
);

CREATE TABLE prices (
  id         BIGSERIAL PRIMARY KEY,
  name       VARCHAR(255) NOT NULL,
  price      NUMERIC(15,2) NOT NULL,
  event_id   BIGINT NULL REFERENCES events(id) ON DELETE CASCADE,
  created_at TIMESTAMPTZ NULL DEFAULT now(),
  updated_at TIMESTAMPTZ NULL DEFAULT now(),
  deleted_at TIMESTAMPTZ NULL
);

CREATE TABLE amenity_price (
  price_id   BIGINT NOT NULL REFERENCES prices(id) ON DELETE CASCADE,
  amenity_id BIGINT NOT NULL REFERENCES amenities(id) ON DELETE CASCADE
);
```

### 4.5 posts (projects)
```sql
CREATE TABLE posts (
  id           BIGSERIAL PRIMARY KEY,
  title        VARCHAR(255) NOT NULL,
  slug         VARCHAR(255) NULL UNIQUE,
  excerpt      TEXT NULL,
  content      TEXT NULL,
  is_published BOOLEAN NOT NULL DEFAULT true,
  created_at   TIMESTAMPTZ NULL DEFAULT now(),
  updated_at   TIMESTAMPTZ NULL DEFAULT now(),
  deleted_at   TIMESTAMPTZ NULL
);  -- media collection: cover (conversions thumb 50x50, card 640x480)
```
> **Đúng source**: bảng `posts` KHÔNG có cột `event_id` — posts là global duy nhất. (Bản nháp trước có ghi `event_id` — đã bỏ.)

### 4.6 contact_messages
```sql
CREATE TABLE contact_messages (
  id         BIGSERIAL PRIMARY KEY,
  event_id   BIGINT NULL DEFAULT 0,   -- 0 = Home Page, X = event X, NULL = legacy
  name       VARCHAR(255) NOT NULL,
  email      VARCHAR(255) NOT NULL,
  subject    VARCHAR(255) NOT NULL,
  message    TEXT NOT NULL,
  read_at    TIMESTAMPTZ NULL,
  created_at TIMESTAMPTZ NULL DEFAULT now(),
  updated_at TIMESTAMPTZ NULL DEFAULT now()
);
CREATE INDEX contact_messages_event_idx ON contact_messages(event_id);
```
> **Đúng source**: cột `event_id` là `unsignedInteger NULL` (không default, không FK). Source code luôn ghi `0` cho home (xem 5.4.1) và accessor `source_label` xử lý `0 = Home Page`. Mặc định `DEFAULT 0` là quyết định an toàn khi viết lại.

### 4.7 company_profile_items (hồ sơ năng lực — home)
```sql
CREATE TABLE company_profile_items (
  id          BIGSERIAL PRIMARY KEY,
  section     VARCHAR(255) NOT NULL,       -- values|why_us|services|solutions|process|roles|models|partners|clients|commitments|warranty
  title       VARCHAR(255) NOT NULL,
  category    VARCHAR(255) NULL,           -- dùng cho section services (nhóm theo category)
  description TEXT NULL,
  link        VARCHAR(255) NULL,
  sort_order  BIGINT NOT NULL DEFAULT 0,
  created_at  TIMESTAMPTZ NULL DEFAULT now(),
  updated_at  TIMESTAMPTZ NULL DEFAULT now(),
  deleted_at  TIMESTAMPTZ NULL
);
CREATE INDEX company_profile_items_section_idx ON company_profile_items(section);
-- media collection: image (conversion thumb 80x80)
```

### 4.8 Danh sách section hợp lệ (company_profile_items.section)
```
values      → Core Values
why_us      → Why Choose Us
services    → Services            (nhóm theo category)
solutions   → Solutions
process     → Implementation Process   (đánh số "Bước N" theo sort_order)
roles       → Team Capabilities
models      → Work Models
partners    → Partners           (hiển thị logo)
clients     → Clients            (hiển thị logo)
commitments → Commitments
warranty    → Warranty Process   (đánh số "Bước N")
```

---

## 5. MÃ NGUỒN & LOGIC NGHIỆP VỤ (để dịch sang Go)

> Đây là **code nguồn gốc** đang chạy. Mỗi khối có chú thích `→ Go: ...` chỉ nơi đích. Chỉ dịch logic, bỏ khái niệm framework (Eloquent/Blade → struct + template/data).

---

### 5.1 HELPERS — Event hiện tại đang chọn

```php
// app/Support/helpers.php
function current_event_id() {
    $id = session('current_event_id');
    if ($id && Event::find($id)) return (int)$id;
    $event = Event::orderBy('id')->first();
    if ($event) { session(['current_event_id' => $event->id]); return (int)$event->id; }
    return null;
}
function current_event() { return Event::find(current_event_id()); }
```
> → Go: `internal/event/service.go` — `func CurrentEventID(ctx) int64` (lấy từ admin session/context, fallback event có id nhỏ nhất), `func CurrentEvent(ctx) *Event`.

---

### 5.2 HOME (công ty SMB+)

#### 5.2.1 Render home — HomeController::index
```php
public function index() {
    $settings = Setting::whereNull('event_id')->pluck('value', 'key');   // map<key,value>
    $posts = Post::where('is_published', 1)->orderBy('created_at', 'desc')->get();
    $profileItems = CompanyProfileItem::orderBy('sort_order')->get()->groupBy('section');
    return view('home', compact('settings', 'posts', 'profileItems'));
}
```
> → Go: `internal/company/home_handler.go` — GET `/` trả về:
> - `settings`: map key→value từ `settings` (event_id NULL)
> - `posts`: published, mới trước, có cover url (thumb + card)
> - `profileItems`: group by section (giữ thứ tự sort_order)
>
> Response JSON gồm 3 field này; Next.js render theo thứ tự section cố định (xem phần 7.2).

#### 5.2.2 Cập nhật hồ sơ công ty — Admin\CompanyProfileController
```php
protected $editableKeys = [
    'company_title','company_subtitle','company_slogan','company_about','company_letter',
    'company_vision','company_mission','company_thanks','company_youtube_link',
    'contact_address','contact_phone','contact_email','contact_website',
    'footer_description','footer_address',
    'footer_twitter','footer_facebook','footer_instagram','footer_googleplus','footer_linkedin',
];
protected $sectionKeys = [
    'sec_letter','sec_about','sec_values','sec_why_us','sec_services','sec_solutions',
    'sec_process','sec_roles','sec_models','sec_projects','sec_partners','sec_clients',
    'sec_commitments','sec_warranty','sec_thanks',
];

// GET /admin/company-profile
public function index() {
    $this->authorizeAdmin();  // abort 403 nếu user không có role "Admin"
    $settings = Setting::whereNull('event_id')->pluck('value','key');
    $posts = Post::orderBy('created_at','desc')->get();
    return view('admin.company-profile.index', compact('settings','posts'));
}

// PUT /admin/company-profile
public function update(Request $request) {
    $this->authorizeAdmin();
    $data = $request->only($this->editableKeys);
    foreach ($this->sectionKeys as $s) {
        $data[$s.'_title']    = $request->input($s.'_title','');
        $data[$s.'_subtitle'] = $request->input($s.'_subtitle','');
    }
    foreach ($data as $key => $value) {
        Setting::updateOrCreate(['key'=>$key,'event_id'=>null], ['value'=>$value]);
    }
    return back()->with('message','Company profile updated successfully.');
}
private function authorizeAdmin() {
    abort_unless(auth()->user()->roles->contains('title','Admin'), 403,
        'Only administrators can edit the company profile.');
}
```
> → Go: `internal/company/handler.go`
> - `GET /admin/company-profile` → `handler.Index` (settings global + posts)
> - `PUT /admin/company-profile` → `handler.Update`: nhận body với các key trong `editableKeys` + cặp `sec_*_title`/`sec_*_subtitle`; **upsert** vào settings (`ON CONFLICT (key) WHERE event_id IS NULL`), event_id NULL.
> - `admin_only` middleware: user phải có role title = `Admin` (không phụ thuộc permission table).

#### 5.2.3 CRUD items hồ sơ năng lực — Admin\CompanyProfileItemsController
```php
// GET /admin/company-profile/items/{section?}
public function index($section = null) {
    $this->authorizeAdmin();
    $section = in_array($section, array_keys(CompanyProfileItem::SECTIONS), true) ? $section : null;
    $query = CompanyProfileItem::orderBy('section')->orderBy('sort_order');
    if ($section) $query->where('section', $section);
    return view('admin.company-profile.items', compact('items','sections','section'));
}

// POST /admin/company-profile/items
public function store(Request $request) {
    $this->authorizeAdmin();
    $validated = $this->validateItem($request);
    $item = CompanyProfileItem::create($validated);
    if ($request->input('image', false))
        $item->addMedia(storage_path('tmp/uploads/'.$request->input('image')))->toMediaCollection('image');
    return redirect()->route('admin.company-profile.items', $item->section);
}
// PUT /admin/company-profile/items/{item}
public function update(Request $request, CompanyProfileItem $item) {
    $this->authorizeAdmin();
    $validated = $this->validateItem($request);
    $item->update($validated);
    if ($request->input('image', false)) {
        if (!$item->image || $request->input('image') !== $item->image->file_name)
            $item->addMedia(storage_path('tmp/uploads/'.$request->input('image')))->toMediaCollection('image');
    } elseif ($item->image) { $item->image->delete(); }
    return redirect()->route('admin.company-profile.items', $item->section);
}
// DELETE /admin/company-profile/items/{item}
public function destroy(CompanyProfileItem $item) { $this->authorizeAdmin(); $item->delete(); return back(); }

// up/down — hoán đổi sort_order với item liền kề cùng section
public function up(CompanyProfileItem $item) {
    $this->authorizeAdmin();
    $prev = CompanyProfileItem::where('section',$item->section)
        ->where('sort_order','<',$item->sort_order)->orderByDesc('sort_order')->first();
    if ($prev) { [$item->sort_order,$prev->sort_order] = [$prev->sort_order,$item->sort_order]; $item->save(); $prev->save(); }
    return back();
}
public function down(CompanyProfileItem $item) {
    $this->authorizeAdmin();
    $next = CompanyProfileItem::where('section',$item->section)
        ->where('sort_order','>',$item->sort_order)->orderBy('sort_order')->first();
    if ($next) { [$item->sort_order,$next->sort_order] = [$next->sort_order,$item->sort_order]; $item->save(); $next->save(); }
    return back();
}
private function validateItem(Request $request) {
    return $request->validate([
        'section'     => ['required','in:values,why_us,services,solutions,process,roles,models,partners,clients,commitments,warranty'],
        'title'       => ['required','min:2'],
        'category'    => ['nullable','string','max:191'],
        'description' => ['nullable','string'],
        'link'        => ['nullable','url','max:191'],
        'sort_order'  => ['nullable','integer','min:0'],
    ]);
}
```
> → Go: `internal/company/item_handler.go`
> - `GET /admin/company-profile/items?section=` (không hợp lệ → lấy hết)
> - `POST /admin/company-profile/items` — validate + upsert media
> - `PUT /admin/company-profile/items/{id}`, `DELETE /admin/company-profile/items/{id}`
> - `POST /admin/company-profile/items/{id}/up`, `POST .../down` — swap sort_order
> - Media image: ghi media table (model_type=`company_profile_item`, collection=`image`).

#### 5.2.4 Model + SECTIONS
```php
// app/CompanyProfileItem.php
public const SECTIONS = [
  'values'=>'Core Values','why_us'=>'Why Choose Us','services'=>'Services',
  'solutions'=>'Solutions','process'=>'Implementation Process','roles'=>'Team Capabilities',
  'models'=>'Work Models','partners'=>'Partners','clients'=>'Clients',
  'commitments'=>'Commitments','warranty'=>'Warranty Process',
];
// conversion: thumb 80x80. accessor image = media cuối của collection 'image' + url + thumbnail
```

---

### 5.3 EVENT (trang con sự kiện)

#### 5.3.1 Event hiển thị + merge settings — EventController
```php
// GET /event
public function index() {
    $event = Event::where('is_active',1)->orderBy('id')->first();   // ưu tiên event active
    if (!$event) $event = Event::orderBy('id')->first();            // fallback event đầu
    if (!$event) abort(404);
    return $this->show($event);
}
// GET /event/{event} — event có thể là slug hoặc id
public function show($event) {
    if (!$event instanceof Event)
        $event = Event::where('slug',$event)->orWhere('id',$event)->firstOrFail();
    $settings = $this->loadSettings($event);   // merge global + event
    $speakers   = Speaker::where('event_id',$event->id)->get();
    $schedules  = Schedule::with('speaker')->where('event_id',$event->id)
                            ->orderBy('start_time','asc')->get()->groupBy('day_number');
    $venues   = Venue::where('event_id',$event->id)->get();
    $hotels   = Hotel::where('event_id',$event->id)->get();
    $galleries= Gallery::where('event_id',$event->id)->get();
    $sponsors = Sponsor::where('event_id',$event->id)->get();
    $faqs     = Faq::where('event_id',$event->id)->get();
    $prices   = Price::with('amenities')->where('event_id',$event->id)->get();
    $amenities= Amenity::with('prices')->where('event_id',$event->id)->get();
    return view('event', compact('event','settings','speakers','schedules','venues',
                                 'hotels','galleries','sponsors','faqs','prices','amenities'));
}
private function loadSettings(Event $event) {
    $global = Setting::whereNull('event_id')->pluck('value','key')->toArray();
    $event  = Setting::where('event_id',$event->id)->pluck('value','key')->toArray();
    return array_merge($global, $event);   // event đè global
}
```
> → Go: `internal/event/handler.go` + `service.go`
> - `GET /event` → tìm event `is_active=true` (thứ tự id tăng), fallback event đầu, 404 nếu không có.
> - `GET /event/{slug|id}` → tìm theo slug hoặc id.
> - Response gồm: `event`, `settings` (merge global+event), `speakers` (kèm photo url), `schedules` (group theo `day_number`, kèm speaker), `venues`/`hotels`/`galleries`/`sponsors` (kèm media), `faqs`, `prices` (kèm danh sách amenity_id), `amenities`.

#### 5.3.2 Speaker page
```php
// GET /speaker/{speaker} — HomeController::view
public function view(Speaker $speaker) {
    $global = Setting::whereNull('event_id')->pluck('value','key');
    $event  = Setting::where('event_id',$speaker->event_id)->pluck('value','key');
    $settings = $global->merge($event);
    return view('speaker', compact('settings','speaker'));
}
```
> → Go: `internal/event/handler.go` — `GET /speaker/{id}` → settings(global+event của speaker) + speaker (photo, name, description, full_description, social).

#### 5.3.3 Admin Events + switch — Admin\EventsController
```php
// POST /admin/events/switch  — đổi event đang quản lý + set active
public function switchEvent(Request $request) {
    $request->validate(['event_id' => ['required','exists:events,id']]);
    $eventId = (int)$request->input('event_id');
    session(['current_event_id' => $eventId]);
    Event::whereKey($eventId)->update(['is_active' => 1]);   // event được chọn → active
    Event::whereKeyNot([$eventId])->update(['is_active' => 0]); // các event khác → inactive
    return back();
}
// index: Event::withCount('speakers')->get();
// store/update: tự sinh slug = slugify(name) nếu để trống
// show: load('speakers','schedules','venues','hotels','galleries','sponsors','faqs','amenities','prices')
// destroy / massDestroy: xóa (soft delete)
```
> → Go: `internal/event/admin_handler.go`
> - `POST /admin/events/switch` body `{event_id}` → set `current_event_id` (session/admin context) + SQL `UPDATE events SET is_active=(id=$1) WHERE deleted_at IS NULL`.
> - CRUD events: store/update auto slugify name nếu slug rỗng.
> - Admin danh sách event kèm `speakers_count`.

#### 5.3.4 CRUD nội dung event (speakers, schedules, venues, hotels, galleries, sponsors, faqs, amenities, prices)
Pattern chung mỗi resource (từ các controller admin):
```php
index()   → abort_if(Gate::denies('<res>_access'),403); query mọi dòng (admin dùng current_event_id để lọc event hiện tại)
store()   → abort_if(Gate::denies('<res>_create'),403); create; nếu có media → addMedia
update()  → abort_if(Gate::denies('<res>_edit'),403); update; xử lý media
destroy() → abort_if(Gate::denies('<res>_delete'),403); delete
massDestroy() → delete whereIn(ids)
```
> → Go: `internal/event/content_handler.go`
> - Mỗi resource = 1 struct + handler: List/Create/Update/Delete/DeleteMany.
> - **Lọc theo admin context**: dùng `current_event_id` để chỉ thao tác dữ liệu event đang chọn.
> - **Media**: speakers→`photo`, venues→`photos`, hotels→`photo`, galleries→`photos`, sponsors→`logo`.
> - **schedules**: có `speaker_id` (nullable) — create/update nhận `speaker_id`.
> - **prices**: có quan hệ many-to-many `amenities` (bảng `amenity_price`) — store/update nhận `amenities: []id` → sync.
> - **Permissions** (gate): resource dùng quyền `<res>_access/_create/_edit/_delete` — ánh xạ từ bảng permissions.

#### 5.3.5 Media upload (Dropzone) — dùng chung mọi resource
```php
// Traits/MediaUploadingTrait
public function storeMedia(Request $request) {
    if (request()->has('size'))
        $this->validate(request(), ['file' => 'max:'.request()->input('size')*1024]);
    if (request()->has('width') || request()->has('height'))
        $this->validate(request(), ['file' => 'image|dimensions:max_width=...,max_height=...']);
    $path = storage_path('tmp/uploads');
    if (!file_exists($path)) mkdir($path, 0755, true);
    $file = $request->file('file');
    $name = uniqid().'_'.trim($file->getClientOriginalName());
    $file->move($path, $name);
    return response()->json(['name'=>$name,'original_name'=>$file->getClientOriginalName()]);
}
```
> → Go: `internal/media/store.go` — POST `<resource>/media` (multipart `file`) → lưu `tmp/uploads/<uniqid>_<filename>` → trả `{name, original_name}`. Frontend (Dropzone) lưu `name` vào input ẩn; khi submit form, controller đọc input ẩn → move file vào media table.
> - `POST /admin/speakers/media`, `/venues/media`, `/hotels/media`, `/galleries/media`, `/sponsors/media`, `/posts/media`, `/company-profile/items/media`.

---

### 5.4 CONTACT

#### 5.4.1 Gửi liên hệ (public)
```php
// POST /contact — HomeController::storeContact
public function storeContact(Request $request) {
    $validator = Validator::make($request->all(), [
        'name'     => ['required','min:4'],
        'email'    => ['required','email'],
        'subject'  => ['required','min:4'],
        'message'  => ['required'],
        'event_id' => ['required','integer','min:0'],
    ]);
    if ($validator->fails()) return response($validator->errors()->first(), 200);
    $data = $request->only(['name','email','subject','message']);
    $data['event_id'] = (int)$request->input('event_id', 0);
    ContactMessage::create($data);
    return response('OK', 200);
}
```
> → Go: `internal/contact/handler.go` — `POST /contact`:
> - Validate: name≥4, email hợp lệ, subject≥4, message không rỗng, event_id≥0.
> - Lỗi → trả text lỗi đầu tiên với HTTP 200 (giữ hành vi cũ). Thành công → text `OK`, HTTP 200.
> - Lưu `ContactMessage{event_id, name, email, subject, message}`.

#### 5.4.2 Quản lý contact (admin) — Admin\ContactMessagesController
```php
// GET /admin/contacts?event_id=
public function index(Request $request) {
    $query = ContactMessage::with('event')->orderByDesc('created_at');
    if ($request->filled('event_id')) $query->where('event_id', $request->input('event_id'));
    $contactMessages = $query->get();
    $events = Event::orderBy('name')->get();
    return view('admin.contact-messages.index', compact('contactMessages','events'));
}
// GET /admin/contacts/{contact}
public function show(ContactMessage $contact) {
    if (is_null($contact->read_at)) $contact->update(['read_at' => now()]);
    return view('admin.contact-messages.show', compact('contact'));
}
// DELETE /admin/contacts/{contact} + massDestroy
```
> → Go: `internal/contact/handler.go`
> - `GET /admin/contacts?event_id=X` → danh sách mới trước, filter theo event_id (0 = Home Page). Filter UI có 3 loại: **All sources**, **Home Page** (`event_id=0`), từng event.
> - `GET /admin/contacts/{id}` → đánh dấu `read_at = now()` nếu chưa đọc.
> - `DELETE /admin/contacts/{id}`, `POST /admin/contacts/destroy` (ids []).
> - Mỗi message kèm `source_label`: `event_id==0 → "Home Page"`, ngược lại tên event.
> - **Lưu ý**: controller contact KHÔNG có Gate/permission check (khác mọi admin controller khác) — mọi user đăng nhập đều xem được. Quyết định khi rewrite: giữ mở hay thêm gate.

---

### 5.5 AUTH & PHÂN QUYỀN

```php
// User model: password luôn bcrypt (setPasswordAttribute: Hash::make nếu cần rehash)
// Role: users(), permissions() many-to-many
// Permission: roles() many-to-many
// Gate: mỗi action admin kiểm tra permission '<res>_<action>'
//   ví dụ event_access, event_create, event_edit, event_delete, speaker_access, ...
// cache: 'auth_gates_permissions' bị forget khi role/permission saved/deleted
```
> → Go: `internal/auth/` + middleware
> - Login: `POST /login` (email+password) → xác thực bcrypt → trả JWT (claim user_id, roles).
> - Middleware `auth`: verify JWT → set user context.
> - Middleware `admin_only`: role title = `Admin`.
> - Gate check: user roles → permissions (JOIN role_user + permission_role + permissions) → map `permission_title → bool`.
> - Password reset: giữ luồng hiện có (email → token → reset), mail log.
> - Sau khi login thành công → redirect `/home` → lại redirect sang `/admin` (route `GET /home` → redirect `/admin`).

### 5.5.1 Middleware & đăng ký (→ Go: `internal/http/middleware/` + `internal/http/router.go`)
| Middleware Laravel | Mục đích | → Go |
|---|---|---|
| `SetLocale` | Đọc `?change_language=` / session `language` / `config('panel.primary_language')` → set locale | middleware `locale` (chọn ngôn ngữ render) |
| `AuthGates` | Định nghĩa Gate động cho từng permission; load `Role::with('permissions')`, **cache 24h** key `auth_gates_permissions`; chạy trên cả web + api | `internal/auth/role_permission.go` (load + cache permissions) |
| `auth` (session) | Bảo vệ toàn bộ `/admin/*` | middleware `auth` (JWT) |
| `guest` alias | `RedirectIfAuthenticated` (login page) | — (không cần) |
| `throttle:60,1` | API giới hạn 60 req/phút | rate-limit middleware cho `/api/*` |
| Exception JSON | Request `api/*` hoặc `expectsJson()` → trả JSON thay vì HTML | router trả JSON khi header `Accept: application/json` |

### 5.5.2 Cấu hình panel (`config/panel.php`)
```
date_format         = "Y-m-d"
time_format         = "H:i:s"
primary_language    = "en"
available_languages = { "en": "English" }
site_title          = "Conference Event"   (trong resources/lang/en/panel.php)
```
> → Go: `internal/config/` — các hằng số format ngày/giờ, ngôn ngữ mặc định, site title.

---

### 5.6 VALIDATION (QUY TẮC — dịch sang validator Go)

> Nguồn: 50 Request classes. Go thư viện: `validator.v10` hoặc tự viết.

**Common pattern** (mọi resource admin): body + `Gate` check trong controller.
- `name`/`title` required (speaker, sponsor, gallery, amenity, post, event, venue, hotel).
- **events** (Store/Update): `name` required duy nhất; slug tự sinh nếu rỗng.
- **schedules**: `day_number` required integer; `start_time` required format `H:i:s`; `title` required.
- **venues**: `name`, `address`, `latitude`, `longitude` required.
- **hotels**: `name` required; `rating` nullable integer 0–5.
- **settings**: `key` required + **unique** (update: unique trừ chính nó); `value` string.
- **prices**: `name`, `price` required; `amenities` nullable array of integer.
- **faqs**: `question`, `answer` required.
- **users**: `name`, `email`, `password` required; `roles` required array of int (update: bỏ password).
- **MassDestroy (15 loại)**: `ids` required array, mỗi `ids.*` phải tồn tại trong bảng.
- **contact (public)**: `name>=4`, `email`, `subject>=4`, `message` required, `event_id` integer>=0.
- **company_profile_items**: `section` ∈ 11 SECTIONS, `title>=2`, `category`/`link` nullable, `link` url, `sort_order` int>=0.

> → Go: mỗi module có struct request + hàm validate trong handler (hoặc `pkg/validator`).

---

### 5.7 POSTS / PROJECTS (home)

```php
// public: Post::where('is_published',1)->orderBy('created_at','desc')
// admin CRUD: title, slug (auto slugify), excerpt, content (HTML), is_published, cover media
// media: cover (thumb 50x50, card 640x480) — view dùng getUrl('card') cho ảnh project
```
> → Go: `internal/post/handler.go` — CRUD posts (global, không lọc event), lọc published cho public, media cover.

---

### 5.8 SETTINGS (admin key-value)

```php
// CRUD settings: key, value (longText), event_id (NULL = global, X = event)
// index (SettingsController): hiển thị GLOBAL + settings của EVENT HIỆN TẠI
//   Setting::where(fn($q) => $q->whereNull('event_id')->orWhere('event_id', current_event_id()))
// store: force event_id = current_event_id()  (không có selector event trong form)
// pluck('value','key') → map dùng khắp nơi (home, event, footer, contact)
```
> → Go: `internal/settings/handler.go`
> - `GET /admin/settings` → trả settings global + của event hiện tại (theo admin context `current_event_id`).
> - `POST /admin/settings` → gán `event_id = current_event_id()`.
> - `PUT /admin/settings/{id}` → cập nhật; key unique (trừ chính nó).
> - Hàm `LoadSettings(global+event)` dùng chung cho home/event/footer.

---

### 5.9 USER / ROLE / PERMISSION (admin)

```php
// CRUD users: name, email, password (bcrypt), roles sync
// CRUD roles: title, permissions sync (dropdown: toàn bộ permissions)
// CRUD permissions: title
```
> → Go: `internal/user/handler.go` — CRUD users/roles/permissions + sync quan hệ (user↔roles, role↔permissions).

---

### 5.10 API v1 (Passport) — LƯU Ý KHÁC BIỆT

> `routes/api.php` + `app/Http/Controllers/Api/V1/Admin/*` (13 controllers) + `app/Http/Resources/Admin/*` (13 resources).
> Endpoint: `GET/POST/PUT/PATCH/DELETE /api/v1/<resource>` cho: permissions, roles, users, settings, speakers, schedules, venues, hotels, galleries, sponsors, faqs, amenities, prices.
> Media endpoint: `POST /api/v1/speakers/media`, `/venues/media`, `/hotels/media`, `/galleries/media`, `/sponsors/media`.
> Auth: `auth:api` (Passport Bearer token) + `throttle:60,1` + `AuthGates`.

**Khác biệt với admin web** (quan trọng khi viết lại):
- API controllers dùng `Model::all()` / `Model::create` **KHÔNG lọc theo `current_event_id()`** — trả về toàn bộ dữ liệu mọi event.
- Media handling giống hệt web (dùng `MediaUploadingTrait`, input ẩn `photo`/`photos`/`logo`).
- Response được bọc bởi Resource (pass-through, trả toàn bộ field model).
- Status code: store → `201`, update → `202`, destroy → `204`, lỗi gate → `403`.
- **Go rewrite**: `internal/<module>/api_handler.go` (tùy chọn) — hoặc gộp chung handler với web, thêm flag "all events". Cần quyết định: giữ API v1 (dùng cho Next.js admin) hay bỏ.

> → Go: nếu Next.js dùng API này làm backend admin → bổ sung `api.go` mỗi module + JWT middleware. Nếu Next.js gọi thẳng handler web → bỏ API v1.

---

## 6. THÊM DỮ LIỆU VÀO DB (CHUẨN SEED)

### 6.1 Nguyên tắc
- Data event nào → `event_id` = id event đó.
- Data global → `event_id NULL` (settings/posts) hoặc `0` (contact home).
- Ảnh seed đặt trong `storage/seeders/` — ghi vào media table với collection tương ứng.
- Mỗi seeder = 1 hàm Go `Seed<Name>(db)` trong `internal/database/seed.go` (hoặc tách file theo nhóm), chạy đúng thứ tự dưới đây.

### 6.2 Trình tự seed hiện tại — CHI TIẾT TỪNG BƯỚC

> Thứ tự bắt buộc: bảng cha trước (permissions, roles, users, events), rồi bảng con (FK), cuối cùng company profile.

| # | Seeder (Laravel) | → Go function | Tạo ra |
|---|---|---|---|
| 1 | PermissionsTableSeeder | `SeedPermissions` | **76 permissions** (danh sách đầy đủ ở 6.4) |
| 2 | RolesTableSeeder | `SeedRoles` | `roles`: **Admin** (id=1), **User** (id=2) |
| 3 | PermissionRoleTableSeeder | `SeedPermissionRole` | Gán permission cho role (xem 6.5) |
| 4 | UsersTableSeeder | `SeedUsers` | `users`: **1 user admin** (id=1) |
| 5 | RoleUserTableSeeder | `SeedRoleUser` | `role_user`: user 1 → role 1 (Admin) |
| 6 | EventsTableSeeder | `SeedEvents` | `events`: **Event 1** + **Event 2** (chi tiết 6.6) |
| 7 | PostsTableSeeder | `SeedPosts` | `posts`: **4 bài** (3 published, 1 draft) |
| 8 | SettingsTableSeeder | `SeedSettings` | `settings`: **14 global** + **6 event-1** (chi tiết 6.7) |
| 9 | SpeakersTableSeeder | `SeedSpeakersE1` | `speakers` event 1: **6 người** (ảnh `speakers/1..6.jpg`) |
| 10 | SchedulesTableSeeder | `SeedSchedulesE1` | `schedules` event 1: **19 lịch** (3 ngày, speaker_id 1-6) |
| 11 | VenuesTableSeeder | `SeedVenuesE1` | `venues` event 1: **1 venue** + **8 ảnh** |
| 12 | HotelsTableSeeder | `SeedHotelsE1` | `hotels` event 1: **3 khách sạn** (ảnh `hotels/1..3.jpg`) |
| 13 | GalleriesTableSeeder | `SeedGalleriesE1` | `galleries` event 1: **1 gallery** + **8 ảnh** |
| 14 | SponsorsTableSeeder | `SeedSponsorsE1` | `sponsors` event 1: **8 nhà tài trợ** (logo `supporters/1..8.png`) |
| 15 | FaqsTableSeeder | `SeedFaqsE1` | `faqs` event 1: **6 câu hỏi** (faker) |
| 16 | AmenitiesTableSeeder | `SeedAmenitiesE1` | `amenities` event 1: **6 tiện ích** |
| 17 | PricesTableSeeder | `SeedPricesE1` | `prices` event 1: **3 gói** (150/250/350) |
| 18 | AmenityPriceTableSeeder | `SeedAmenityPriceE1` | `amenity_price`: price 1→[1,2,3], 2→[1,2,3,4], 3→[1..6] |
| 19 | SampleEventTwoAndContactsSeeder | `SeedEventTwoAndContacts` | Event 2 đầy đủ + **5 contact messages** (chi tiết 6.8) |
| 20 | CompanyProfileContentSeeder | `SeedCompanyProfile` | settings công ty + **79 company_profile_items** (chi tiết 6.9) |

### 6.3 Quy ước seed media (→ Go)
- Với mỗi record có ảnh: insert record → ghi `media` với `model_type`/`model_id`/`collection_name` + copy file từ `storage/seeders/<folder>/<file>` sang thư mục media.
- Collection map:
  - speakers → `photo` (1 file)
  - venues → `photos` (nhiều file)
  - hotels → `photo` (1 file)
  - galleries → `photos` (nhiều file)
  - sponsors → `logo` (1 file)
  - posts → `cover` (1 file)
  - company_profile_items → `image` (1 file, optional)
- Seed event 1 lấy từ các file đặt tên theo thứ tự: `speakers/1.jpg...6.jpg`, `hotels/1.jpg...3.jpg`, `gallery/1.jpg...8.jpg`, `venue-gallery/1.jpg...8.jpg`, `supporters/1.png...8.png`.
- **Data dùng Faker (sinh ngẫu nhiên)** — không thể tái tạo giống hệt; Go nên dùng **text mặc định cố định**:
  - `posts.content` → 2 đoạn `<p>` faker paragraphs(4) + paragraphs(3) — thay bằng 2 đoạn HTML tĩnh.
  - `speakers.full_description` (event 1) → faker paragraph — thay bằng mô tả tĩnh.
  - `faqs` (event 1) → faker sentence/paragraph — thay bằng 6 câu hỏi-đáp tĩnh.

### 6.4 Danh sách 76 permissions (bước 1)
> Quy tắc: mỗi resource có 5 quyền `_create/_edit/_show/_delete/_access`, riêng `user_management_access` là quyền cha.

```
1  user_management_access
2  permission_create   3  permission_edit    4  permission_show
5  permission_delete   6  permission_access
7  role_create         8  role_edit          9  role_show
10 role_delete         11 role_access
12 user_create         13 user_edit          14 user_show
15 user_delete         16 user_access
17 setting_create      18 setting_edit       19 setting_show
20 setting_delete      21 setting_access
22 speaker_create      23 speaker_edit       24 speaker_show
25 speaker_delete      26 speaker_access
27 schedule_create     28 schedule_edit      29 schedule_show
30 schedule_delete     31 schedule_access
32 venue_create        33 venue_edit         34 venue_show
35 venue_delete        36 venue_access
37 hotel_create        38 hotel_edit         39 hotel_show
40 hotel_delete        41 hotel_access
42 gallery_create      43 gallery_edit       44 gallery_show
45 gallery_delete      46 gallery_access
47 sponsor_create      48 sponsor_edit       49 sponsor_show
50 sponsor_delete      51 sponsor_access
52 faq_create          53 faq_edit           54 faq_show
55 faq_delete          56 faq_access
57 amenity_create      58 amenity_edit       59 amenity_show
60 amenity_delete      61 amenity_access
62 price_create        63 price_edit         64 price_show
65 price_delete        66 price_access
67 event_create        68 event_edit         69 event_show
70 event_delete        71 event_access
72 post_create         73 post_edit          74 post_show
75 post_delete         76 post_access
```

### 6.5 Gán quyền cho role (bước 3)
- **Role 1 (Admin)**: được gán **toàn bộ 76 permissions**.
- **Role 2 (User)**: được gán permissions **loại trừ** các tiền tố sau:
  - bắt đầu bằng `user_`
  - bắt đầu bằng `role_`
  - bắt đầu bằng `permission_`
  - bắt đầu bằng `event_`
  - bắt đầu bằng `post_`
- Tức User KHÔNG có quyền quản lý user/role/permission/event/post. (Quy tắc check tiền tố trong code: `substr($title,0,5)` cho `user_`/`role_`/`post_`, `substr($title,0,11)` cho `permission_`, `substr($title,0,6)` cho `event_`.)
- Còn lại (settings, speakers, schedules, venues, hotels, galleries, sponsors, faqs, amenities, prices) thì User có.

### 6.6 Events (bước 6)
| id | name | slug | start_date | end_date | is_active |
|---|---|---|---|---|---|
| 1 | The Annual Marketing Conference | annual-marketing-conference | 2026-12-10 | 2026-12-12 | true |
| 2 | Tech Summit 2026 | tech-summit-2026 | 2027-03-15 | 2027-03-17 | true |

> Lưu ý: cả 2 đều `is_active=true` lúc seed; khi admin bấm "switch" event nào thì event đó thành `true`, các event khác thành `false`.

### 6.7 Settings (bước 8)
**14 global** (event_id = NULL): `company_title`, `company_subtitle`, `company_about`, `company_youtube_link`, `contact_address`, `contact_phone`, `contact_email`, `footer_description`, `footer_address`, `footer_twitter`, `footer_facebook`, `footer_instagram`, `footer_googleplus`, `footer_linkedin`.

**6 event-1** (event_id = 1): `title` (`The Annual<br><span>Marketing</span> Conference`), `subtitle` (`10-12 December, Downtown Conference Center, New York`), `youtube_link`, `about_description`, `about_where` (`Downtown Conference Center, New York`), `about_when` (`Monday to Wednesday<br>10-12 December`).

### 6.8 Event 2 + Contact messages (bước 19) — CHI TIẾT
**Settings event 2** (event_id = 2):
```
title        → "Tech<br><span>Summit</span> 2026"
subtitle     → "15-17 March, Silicon Valley Convention Hall, San Francisco"
youtube_link → "https://www.youtube.com/watch?v=jDDaplaOz7Q"
about_description → "A gathering of the brightest minds in technology to share, learn and inspire. Three days of keynotes, workshops and networking with leaders from AI, cloud computing, cybersecurity and product engineering."
about_where  → "Silicon Valley Convention Hall, San Francisco"
about_when   → "Monday to Wednesday<br>15-17 March"
```
**Speakers (6)** — mỗi speaker có `twitter/facebook/linkedin = "#"`, ảnh `speakers/1..6.jpg`, `event_id=2`:
| # | name | description | full_description |
|---|---|---|---|
| 1 | Alice Nguyen | AI Research Lead | Alice leads applied AI research with a focus on LLMs... |
| 2 | David Patel | CTO, CloudScale | David has spent 15 years building distributed systems... |
| 3 | Maria Lopez | Head of Product, InnoWorks | Maria is a product leader who has shipped developer tools... |
| 4 | Tom Becker | Security Architect, Guardline | Tom specializes in zero-trust architectures... |
| 5 | Sara Kim | Data Engineering Director, Quantix | Sara builds real-time data platforms... |
| 6 | James Okafor | VP Engineering, FinTech Labs | James oversees engineering at a fast-growing fintech... |

**Schedules (12)** — 3 ngày, `start_time` 09:00–16:00, một số gắn `speaker_id` (1-6), một số `NULL` (Registration, Panel, Fireside):
- Day 1: 09:00 Registration (null), 10:00 Opening Keynote (s1), 11:30 Cloud Platforms (s2), 14:00 Product-Led Growth (s3), 16:00 Zero-Trust Security (s4)
- Day 2: 09:30 Real-Time Data (s5), 11:00 Scaling Teams (s6), 14:00 AI Workshop (s1), 16:00 Panel (null)
- Day 3: 09:30 Developer Experiences (s3), 11:00 Fireside Chat (s2), 14:00 Closing Keynote (s6)

**Venue (1)**: `Silicon Valley Convention Hall, San Francisco`, address `747 Howard St, San Francisco, CA 94103`, lat `37.78497`, lon `-122.40105`, 8 ảnh `venue-gallery/1..8.jpg`.

**Hotels (3)**: `Grand Bay Hotel` (5★), `City Lights Inn` (4★), `Union Square Suites` (3★); ảnh `hotels/1..3.jpg`.

**Gallery (1)**: name `Tech Summit 2026`, 8 ảnh `gallery/1..8.jpg`.

**Sponsors (8)**: Strider, Runtastic, EditShare, InFocus, gategroup, Cadent, Ceph, Alitalia (link `#`); logo `supporters/1..8.png`.

**Faqs (6)**: agenda, parking, meals, refund, recordings, dress code.

**Amenities (6)**: Regular Seating, Coffee Break, Custom Badge, Community Access, Workshop Access, After Party.

**Prices (3)** + pivot `amenity_price`:
| price | name | price | amenities (id) |
|---|---|---|---|
| 1 | Standard Access | 120 | [1, 2, 3] |
| 2 | Pro Access | 220 | [1, 2, 3, 4] |
| 3 | Premium Access | 320 | [1, 2, 3, 4, 5, 6] |

**Contact messages (5)**:
| # | event_id | name | email | subject |
|---|---|---|---|---|
| 1 | 1 | John Smith | john@example.com | Question about ticket upgrade |
| 2 | 1 | Emily Davis | emily@example.com | Group discount |
| 3 | 2 | Michael Brown | michael@example.com | Sponsorship inquiry |
| 4 | 2 | Sarah Wilson | sarah@example.com | Accommodation help |
| 5 | NULL | Robert Garcia | robert@example.com | Partnership opportunity |

> Trong Go/DB mới, contact #5 phải lưu `event_id = 0` (Home Page) thay vì NULL — theo quy tắc `source_label` (xem 5.4).

### 6.9 Company profile (bước 20) — CHI TIẾT

**A. Settings công ty (global, event_id NULL)** — dùng `updateOrCreate` theo key:
```
company_title     → "SMB+<br><span>Solution for Business Plus</span>"
company_subtitle  → "Tư vấn & Phát triển phần mềm - Đối tác chuyển đổi số tin cậy"
company_slogan    → "Digital Transformation Partner"
company_about     → (đoạn giới thiệu công ty, HTML)
company_letter    → (thư ngỏ của Ban Giám đốc, HTML)
company_vision    → (tầm nhìn)
company_mission   → (sứ mệnh)
company_thanks    → (lời cảm ơn)
contact_website   → "https://smbplus.vn"
```
**B. Section title/subtitle** — 15 cặp `sec_<name>_title` + `sec_<name>_subtitle`:
```
sec_letter       → "Thư ngỏ" / "Lời ngỏ từ Ban Giám đốc"
sec_about        → "Giới thiệu SMB+" / "Đối tác công nghệ tin cậy của doanh nghiệp"
sec_values       → "Giá trị cốt lõi" / ""
sec_why_us       → "Vì sao chọn SMB+" / "Lợi thế đồng hành cùng chúng tôi"
sec_services     → "Dịch vụ" / "Giải pháp phần mềm toàn diện"
sec_solutions    → "Giải pháp" / "Nền tảng triển khai cho doanh nghiệp"
sec_process      → "Quy trình triển khai" / "8 bước chuyên nghiệp, minh bạch"
sec_roles        → "Năng lực đội ngũ" / "Chuyên gia giàu kinh nghiệm trong mọi vai trò"
sec_models       → "Mô hình làm việc" / "Linh hoạt theo nhu cầu doanh nghiệp"
sec_projects     → "Dự án tiêu biểu" / "Các dự án chúng tôi đã thực hiện cho khách hàng"
sec_partners     → "Đối tác" / "Các đối tác công nghệ của SMB+"
sec_clients      → "Khách hàng" / "Sự tin tưởng của khách hàng là thành công của chúng tôi"
sec_commitments  → "Cam kết" / "Những cam kết của chúng tôi với khách hàng"
sec_warranty     → "Quy trình bảo hành" / "Hỗ trợ nhanh chóng, đúng quy trình"
sec_thanks       → "Lời cảm ơn" / ""
```
**C. Company profile items — 79 items / 11 section** (đã bỏ section `tech`):
| section | số lượng | loại hiển thị |
|---|---|---|
| values | 5 | card text (trong #about) |
| why_us | 4 | card title + description |
| services | 22 | nhóm theo `category` (4 nhóm: Software Engineering, Technology Consulting, Testing Services, SaaS Solutions) |
| solutions | 10 | card text |
| process | 8 | "Bước N" theo sort_order |
| roles | 8 | card text |
| models | 5 | card text |
| partners | 3 | logo (ảnh hoặc text) |
| clients | 4 | logo (ảnh hoặc text) |
| commitments | 5 | card text |
| warranty | 5 | "Bước N" theo sort_order |
| **Tổng** | **79** | |

> Ghi chú: `CompanyProfileItem::SECTIONS` không còn `tech` nữa — validate `section` chỉ chấp nhận 11 giá trị trên.

---

## 7. UI / DESIGN SYSTEM (GIỮ NGUYÊN)

> Frontend Next.js render đúng các class/token bên dưới để UI không đổi.

### 7.1 Tokens (public/css/design-system.css)
```
--canvas-soft:#f2f0eb  --canvas:#fff  --surface:#fff  --hairline:#e7e7e7  --field-border:#d6dbde
--ink:rgba(0,0,0,.87)  --ink-secondary/-muted:.58  --ink-faint:.38
--primary:#00754a  --primary-active:#006241  --secondary:#1e3932  --green-uplift:#2b5148
--green-light:#d4e9e2  --gold:#cba258  --on-primary:#fff
--danger:#c82014  --warning:#fbbc05
--accent-sky/purple/pink/orange/teal/green/brown
--font-sans:"Inter",...  radius:4/6/12/12/16/50px  spacing:4/8/12/16/24/28/32px
shadow:--shadow-soft, --shadow-elevated, --shadow-frap
```
Class: `.display-1 .heading-1/2/3 .body-md/sm .eyebrow .badge-pill .feature-card-elevated .button-primary/secondary .hero-sticker .hero-star`.

### 7.1.1 Public layout — thư viện JS/CSS (layouts/main.blade.php)
> Next.js cần thay thế bằng npm packages tương đương (giữ class/behavior).
```
CSS : bootstrap.min, font-awesome.min, animate.min, venobox.css,
      owl.carousel.min.css, design-system.css, style.css, theme.css
JS  : jquery.min, jquery-migrate.min, bootstrap.bundle.min, easing.min,
      hoverIntent.js, superfish.min.js, wow.min.js, venobox.min.js,
      owl.carousel.min.js, contactform.js, app.js
```
Behavior trong `app.js`: back-to-top, sticky header, `new WOW().init()`, venobox init, superfish menu, mobile nav, smooth scroll, owlCarousel gallery, buy-ticket modal inject.
Behavior trong `contactform.js`: validate client-side qua `data-rule` (name/email/subject/message), AJAX POST `/contact`, toast notification (toastStack).
> NPM thay thế: bootstrap, font-awesome, wow.js, venobox, owl.carousel, superfish, jquery (hoặc viết lại bằng framework hiện đại — nhưng giữ UX).

### 7.1.2 Admin layout — stack AdminLTE (layouts/admin.blade.php)
```
Bootstrap 4.2, AdminLTE v3, DataTables 1.10.19 + Buttons/Select (copy/csv/excel/pdf/print/colvis),
pdfmake, jszip, select2, moment, bootstrap-datetimepicker, dropzone 5.5.1, CKEditor 5,
css: adminltev3, custom, design-system, admin-theme
```
> Next.js admin có thể dùng DataTables equivalent (hoặc render table đơn giản), Select2 → combobox, Dropzone → upload component, CKEditor → rich text editor.

### 7.2 Thứ tự section Home (render cố định)
```
#intro → #letter (nếu company_letter) → #about (company_about + vision/mission + values)
→ #why-us → #services (group category) → #solutions → #process ("Bước N")
→ #team-capability (roles) → #work-models (models) → #projects (posts)
→ #partners (logo) → #clients (logo) → #commitments → #warranty ("Bước N")
→ #thanks (nếu company_thanks) → contact
```

### 7.3 Event page (render cố định)
```
sections/intro (title, subtitle, youtube) → about → speakers → schedule (group day)
→ venues → hotels → gallery → sponsors → faq → buy_ticket (prices+amenities check/cross)
→ contact (form: hidden event_id)
```
- `schedule`: group theo `day_number`, format `start_time` thành `h:i A` (VD 09:30 AM).
- `venues`: nhúng Google Maps iframe từ `latitude`/`longitude`.

### 7.4 TÍNH NĂNG "CHẾT"/TĨNH — cần quyết định khi rewrite
| Tính năng | Trạng thái hiện tại | Khuyến nghị Go/Next.js |
|---|---|---|
| `sections/subscribe.blade.php` (newsletter) | Form `action="#"`, **không render ở trang nào** | Bỏ, hoặc xây newsletter thật |
| `buy_ticket` modal | Form order `action="#"`, **không có backend bán vé** — chỉ chọn loại vé | Giữ giao diện; quyết định có bán vé hay bỏ nút |
| `welcome.blade.php` | Trang Laravel mặc định, không truy cập được | Bỏ |
| `RegisterController` / `VerificationController` | Tồn tại nhưng **không có route** đăng ký/xác minh | Bỏ nếu không cần |
| `Route::redirect('/home','/admin')` | Dùng làm đích sau login | Giữ luồng login → admin |

---

## 8. API CONTRACT

### 8.1 Public
| Method | Path | Mô tả |
|---|---|---|
| GET | `/` | Home: `{settings, posts, profileItems}` |
| GET | `/event` | Event active (fallback đầu, 404 nếu rỗng) |
| GET | `/event/{slug\|id}` | Event theo slug hoặc id |
| GET | `/speaker/{id}` | Speaker + settings |
| POST | `/contact` | Tạo contact message (trả text `OK`/lỗi, HTTP 200) |

### 8.2 Admin (auth + admin_only cho company; gate cho CRUD)
| Method | Path | Mô tả |
|---|---|---|
| POST | `/login` | email+password → token |
| GET/PUT | `/admin/company-profile` | xem/cập nhật hồ sơ công ty |
| GET/POST | `/admin/company-profile/items` | list/create item |
| PUT/DELETE | `/admin/company-profile/items/{id}` | update/delete item |
| POST | `/admin/company-profile/items/{id}/up\|down` | sắp xếp |
| POST | `/admin/events/switch` | đổi event + set active |
| CRUD | `/admin/events`, `/admin/posts`, `/admin/settings` | ... |
| CRUD | `/admin/speakers`, `/admin/schedules`, `/admin/venues`, `/admin/hotels`, `/admin/galleries`, `/admin/sponsors`, `/admin/faqs`, `/admin/amenities`, `/admin/prices` | nội dung event |
| GET/DELETE | `/admin/contacts` (+ `/{id}`, `/destroy`) | contact messages |
| CRUD | `/admin/users`, `/admin/roles`, `/admin/permissions` | auth admin |
| POST | `<resource>/media` | upload media (Dropzone) |

### 8.3 API v1 (Passport Bearer token — `/api/v1/...`, throttle 60/min)
> Lưu ý: API v1 **không lọc theo event** (trả toàn bộ mọi event). Quyết định dùng hay bỏ khi rewrite (xem 5.10).

| Method | Path | Mô tả |
|---|---|---|
| CRUD | `/api/v1/permissions`, `/api/v1/roles`, `/api/v1/users` | auth admin |
| CRUD | `/api/v1/settings` | settings (không lọc event) |
| CRUD | `/api/v1/speakers`, `/api/v1/schedules`, `/api/v1/venues`, `/api/v1/hotels`, `/api/v1/galleries`, `/api/v1/sponsors`, `/api/v1/faqs`, `/api/v1/amenities`, `/api/v1/prices` | nội dung event (toàn bộ) |
| POST | `/api/v1/speakers/media`, `/api/v1/venues/media`, `/api/v1/hotels/media`, `/api/v1/galleries/media`, `/api/v1/sponsors/media` | upload media |

Response status: store → 201, update → 202, destroy → 204, gate fail → 403. Response shape: JSON bọc bởi Resource (toàn bộ field model + media url).
