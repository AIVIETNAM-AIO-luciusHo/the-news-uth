# The News UTH

Đồ án website tin tức PHP + MySQL, có giao diện người đọc và quản trị.

## Trạng thái
Đã triển khai nền tảng PHP SSR/MVC nhẹ, PDO/MySQL, schema và seed tổng hợp. Có trang chủ mẫu, `/admin/login` giữ chỗ, trang 404/500. Chưa có xác thực, CRUD, upload hay editor. Kiểm chứng kỹ thuật đã chạy; T012 (thành viên khác dựng lại) còn chờ. Bộ công cụ GitHub Spec Kit khởi tạo từ Specify CLI 1.2.3.

## Tài liệu bắt đầu
- [Hướng dẫn Spec Kit bằng tiếng Việt](docs/github/spec-kit-workflow.md): lệnh dùng trong agent và quy trình phát triển.
- [Nguyên tắc dự án](.specify/memory/constitution.md): kiến trúc, bảo mật, chất lượng và bàn giao.
- [Lộ trình theo module](docs/roadmap.md): thứ tự triển khai và phụ thuộc.
- [Đặc tả nền tảng đầu tiên](specs/001-project-foundation/spec.md), [kế hoạch](specs/001-project-foundation/plan.md), [công việc](specs/001-project-foundation/tasks.md).
- [Hướng dẫn đóng góp](AGENTS.md).

## Môi trường

Đã kiểm chứng PHP **8.4.27**, MySQL Community **8.4.13**, Docker **29.5.2** trên Linux/WSL2. PHP cần `PDO`, `pdo_mysql`; không cần Composer hoặc Node để chạy ứng dụng. Quyết định và nguồn phiên bản ở [research.md](specs/001-project-foundation/research.md).

## Chạy demo bằng Docker (đã kiểm chứng)

Chạy từ thư mục gốc repo. Tên container/network dưới đây dành riêng cho demo; cần chưa tồn tại trên máy mới. Cổng host là `127.0.0.1:18080`, không công khai DB.

1. Build PHP có driver MySQL và tạo mạng:

```bash
docker build -f docs/test/php.Dockerfile -t news-uth-foundation-php:8.4 .
docker network create news-uth-foundation-test
```

2. Tạo `config/mysql.local.env` cục bộ (đã được `.gitignore` bỏ qua), thay các giá trị `REPLACE_...` bằng mật khẩu của riêng bạn:

```dotenv
MYSQL_ROOT_PASSWORD=REPLACE_ROOT_PASSWORD
MYSQL_DATABASE=news_uth_verified
MYSQL_USER=news_uth
MYSQL_PASSWORD=REPLACE_APP_PASSWORD
```

```bash
chmod 600 config/mysql.local.env
docker run -d --name news-uth-foundation-db --network news-uth-foundation-test --env-file config/mysql.local.env mysql:8.4
```

Đợi log báo `ready for connections` (`docker logs news-uth-foundation-db`). Dữ liệu nằm trong volume Docker tự tạo; dừng container không reset DB.

3. Tạo config ứng dụng:

```bash
cp config/config.example.php config/config.local.php
```

Sửa `database` trong `config.local.php`: `host` = `news-uth-foundation-db`, `port` = `3306` (số nguyên), `name` = `news_uth_verified`, `username` = `news_uth`, `password` trùng `MYSQL_PASSWORD`. Giữ config này ở máy mình.

4. Import **schema trước, seed sau**, vào DB trống vừa tạo:

```bash
docker exec -i news-uth-foundation-db sh -c 'MYSQL_PWD="$MYSQL_PASSWORD" mysql -u "$MYSQL_USER" news_uth_verified' < database/schema.sql
docker exec -i news-uth-foundation-db sh -c 'MYSQL_PWD="$MYSQL_PASSWORD" mysql -u "$MYSQL_USER" news_uth_verified' < database/seed.sql
```

Chỉ chạy seed khi schema import thành công. Không dùng `--force`. Cả hai file dành cho import một lần: chạy lại phải báo table/duplicate key, không tự xóa hoặc ghi đè. MySQL DDL không rollback toàn file; nếu import schema lỗi, kiểm tra nguyên nhân và dùng **database thử trống khác**, không tiếp tục seed vào schema dở dang. Không chạy DROP/TRUNCATE/reset trên DB có dữ liệu cần giữ. Nếu cần reset demo, chỉ xóa database/volume thử sau khi kiểm tra tên và sao lưu; repo không tự reset.

Seed gồm 2 users, 2 categories, 4 articles và 2 ảnh SVG minh họa có sẵn. Tài khoản `admin@example.test`, `editor@example.test` có mật khẩu demo `FoundationDemo!2026`, chỉ lưu bcrypt hash trong DB. Chưa có chức năng đăng nhập; tuyệt đối không dùng tài khoản demo cho production.

5. Chạy PHP với document root `public/`:

```bash
docker run -d --name news-uth-foundation-web --network news-uth-foundation-test -p 127.0.0.1:18080:8080 -v "$PWD:/app:ro" news-uth-foundation-php:8.4 php -S 0.0.0.0:8080 -t public public/index.php
```

Mở [trang chủ](http://127.0.0.1:18080/), [trang giữ chỗ quản trị](http://127.0.0.1:18080/admin/login). `/khong-ton-tai` trả 404. Thiếu config/DB không truy cập được khiến trang chủ trả 500 chung; log chỉ ghi `[foundation] request_failed`.

Dừng demo bằng `docker stop news-uth-foundation-web news-uth-foundation-db`; chạy lại bằng `docker start news-uth-foundation-db news-uth-foundation-web`. Không tạo lại/import lại khi chỉ cần khởi động lại.

Nếu đã cài PHP 8.4 + pdo_mysql và MySQL 8.4 trên host, cấu hình host DB tương ứng, import bằng MySQL CLI, rồi dùng lệnh tương đương `php -S 127.0.0.1:8080 -t public public/index.php`. Biến thể chạy trực tiếp trên host chưa được thử trong phiên này. PHP built-in server chỉ dành cho phát triển. Không đặt document root ở gốc repo.

## Kiểm tra

```bash
docker exec news-uth-foundation-web sh -c 'find app config public views docs/test -name "*.php" -print0 | xargs -0 -n1 php -l'
docker exec news-uth-foundation-web php docs/test/check-database.php
python3 docs/test/check-http.py http://127.0.0.1:18080
python3 docs/test/check-errors.py http://127.0.0.1:18080
```

DB check dành cho **DB demo thử riêng có seed gốc**, kiểm tra hash/FK/constraints và rollback mọi mutation. HTTP check cần Python 3, không cần cài package. Error check tạm thay config cục bộ rồi khôi phục bằng `finally`: chạy riêng khi không có người truy cập demo; không ngắt tiến trình giữa chừng. Không chạy kiểm thử lỗi và chụp UI đồng thời.

Chụp UI tùy chọn cần Playwright/Chromium có sẵn ngoài repo: `BASE_URL=http://127.0.0.1:18080 PLAYWRIGHT_MODULE=/absolute/path/to/playwright node docs/test/capture-ui.cjs`. Đây là công cụ kiểm thử, không phải phụ thuộc ứng dụng.

[Kết quả thực tế, ảnh UI và hướng dẫn bàn giao](docs/test/foundation.md). Chưa có framework/coverage threshold; `php -l` chỉ kiểm tra cú pháp. Một thành viên khác cần chạy quy trình trên từ checkout sạch và bổ sung kết quả T012.
