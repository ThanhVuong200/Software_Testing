# Software Testing — Website bán dép MWC

Dự án website thương mại điện tử bán dép/giày thương hiệu **MWC**, kèm trang quản trị và bộ **unit test** (PHPUnit) phục vụ môn Kiểm thử phần mềm.

## Thành viên nhóm

| STT | Họ tên |
|-----|--------|
| 1 | Lê Đức Nam |
| 2 | Tiêu Quang Phú |
| 3 | Nguyễn Văn Ngàn |
| 4 | Phạm Thanh Vương |

## Tính năng chính

### Cửa hàng (phía khách hàng)

- Trang chủ, danh sách sản phẩm, chi tiết sản phẩm
- Tìm kiếm và lọc theo danh mục
- Giỏ hàng: thêm, cập nhật số lượng, xóa sản phẩm
- Đăng ký / đăng nhập tài khoản
- Quy trình thanh toán: địa chỉ, vận chuyển, phương thức thanh toán
- Theo dõi đơn hàng, quản lý tài khoản cá nhân

### Trang quản trị (`/admin`)

- Đăng nhập quản trị viên
- Quản lý sản phẩm, danh mục, khách hàng, người dùng
- Quản lý đơn hàng và hóa đơn

### Kiểm thử (`Test_Unit/`)

- Unit test với **PHPUnit 9.6**
- Các module được kiểm thử: sản phẩm, giỏ hàng, khách hàng, người dùng, địa chỉ, thanh toán

## Công nghệ sử dụng

| Thành phần | Công nghệ |
|------------|-----------|
| Ngôn ngữ | PHP |
| Cơ sở dữ liệu | MySQL / MariaDB |
| Giao diện | HTML, CSS, Bootstrap, jQuery |
| Kiểm thử | PHPUnit ^9.6 |

## Cấu trúc thư mục

```
Software_Testing/
├── admin/                 # Trang quản trị
│   ├── classes/           # Class xử lý nghiệp vụ admin
│   ├── config/            # Cấu hình DB cho admin
│   └── pages/             # Giao diện quản trị
├── classes/               # Class nghiệp vụ phía cửa hàng
├── Test_Unit/             # Bộ unit test PHPUnit
├── sql/
│   └── web2.sql           # Script tạo DB và dữ liệu mẫu
├── config.php             # Kết nối MySQL (phía cửa hàng)
├── index.php              # Trang chủ
├── cart.php, shop-gird.php, single-product.php, ...
├── composer.json          # Dependency dev (PHPUnit)
└── vendor/                # Thư viện Composer (PHPUnit)
```

## Yêu cầu hệ thống

- PHP >= 7.4 (khuyến nghị PHP 8.x)
- MySQL hoặc MariaDB
- Apache/Nginx (hoặc XAMPP, Laragon, WAMP)
- [Composer](https://getcomposer.org/) (để cài PHPUnit)

## Cài đặt

### 1. Clone repository

```bash
git clone <url-repo>
cd Software_Testing
```

### 2. Cài dependency kiểm thử

```bash
composer install
```

### 3. Tạo cơ sở dữ liệu

1. Mở phpMyAdmin hoặc MySQL CLI.
2. Tạo database tên `web2` (hoặc tên khác, nhưng cần đồng bộ cấu hình).
3. Import file `sql/web2.sql`.

### 4. Cấu hình kết nối database

Chỉnh thông tin trong hai file sau cho khớp môi trường local:

**`config.php`** (cửa hàng):

```php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'web2';
```

**`admin/config/config.php`** (quản trị):

```php
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "web2");
```

### 5. Chạy ứng dụng

- Đặt thư mục project vào `htdocs` (XAMPP) hoặc document root của web server.
- Truy cập:
  - Cửa hàng: `http://localhost/Software_Testing/`
  - Admin: `http://localhost/Software_Testing/admin/`

## Chạy unit test

Chạy toàn bộ test trong thư mục `Test_Unit`:

```bash
./vendor/bin/phpunit Test_Unit
```

Chạy từng file test:

```bash
./vendor/bin/phpunit Test_Unit/ProductTest.php
./vendor/bin/phpunit Test_Unit/CartTest.php
./vendor/bin/phpunit Test_Unit/CustomerTest.php
./vendor/bin/phpunit Test_Unit/UserTest.php
./vendor/bin/phpunit Test_Unit/AddressTest.php
./vendor/bin/phpunit Test_Unit/PaymentTest.php
```

Trên Windows (PowerShell):

```powershell
.\vendor\bin\phpunit Test_Unit
```

> **Lưu ý:** Một số test (ví dụ `ProductTest`) cần kết nối database thật và dữ liệu mẫu từ `web2.sql`. Test giỏ hàng (`CartTest`) dùng mock `mysqli` nên không bắt buộc DB khi chạy.

## Danh sách test case

| File | Nội dung kiểm thử |
|------|-------------------|
| `ProductTest.php` | Tìm kiếm sản phẩm, phân trang, lọc theo danh mục |
| `CartTest.php` | Thêm/xóa/cập nhật giỏ, tính tổng, chuyển hướng checkout |
| `CustomerTest.php` | Đổi trạng thái khách hàng (active/locked) |
| `UserTest.php` | Đổi trạng thái người dùng (active/locked) |
| `AddressTest.php` | Validate địa chỉ giao hàng |
| `PaymentTest.php` | Phương thức thanh toán, tính tổng tiền |

## Ghi chú

- Dự án dùng cho mục đích học tập và kiểm thử phần mềm.
- Không nên dùng cấu hình mặc định (`root` / mật khẩu rỗng) trên môi trường production.
- Thư mục `vendor/` được tạo bởi Composer; chạy `composer install` sau khi clone nếu chưa có.
