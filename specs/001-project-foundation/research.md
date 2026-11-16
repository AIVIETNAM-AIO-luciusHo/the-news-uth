# Research: Nền tảng ứng dụng

Ngày quyết định: 2026-11-16. Môi trường host có Docker 29.5.2, chưa có PHP/MySQL CLI. Dùng container thử riêng; không truy cập database có sẵn.

- Runtime chọn và đã xác minh: PHP 8.4.27 CLI, MySQL 8.4.13 LTS. Image PHP digest `sha256:d63e8b80cd4c6cfd01cfa7c910522e299cdc7ca1a2b6d6563db48df3c17e6c55`; MySQL digest `sha256:a81d043930e51745522bd5c430961cf8286f0a23f0a9a2a6e0108f7ea71b0902`.
- Extension: PDO và pdo_mysql; password_hash/password_verify thuộc PHP core. Không thêm Composer, editor, bộ lọc HTML hoặc frontend dependency trong feature này.
- Local server chọn: `php -S 127.0.0.1:8080 -t public public/index.php`. Đây là server phát triển, không dùng để triển khai production. Container bind 0.0.0.0 bên trong nhưng chỉ publish 127.0.0.1 trên host.
- MVC nhẹ: core xử lý config, PDO, router và render; controller điều phối; model kiểm tra kết nối bằng prepared statement; view chỉ hiển thị. Chưa có nghiệp vụ cần service, giữ thư mục service cho module sau.
- Schema: InnoDB, utf8mb4_0900_ai_ci, ID BIGINT UNSIGNED, thời gian UTC; FK RESTRICT cho tác giả/chuyên mục, CASCADE cho ảnh thuộc bài. ENUM giới hạn role/status. Seed chỉ nhập một lần trên schema rỗng; không DROP/TRUNCATE/IGNORE hay upsert che xung đột.
- Homepage mẫu không render nội dung bài từ database. Chưa có editor/HTML không tin cậy; mọi chuỗi động được escape.
- Lỗi: response 500 chung, log chỉ mã sự kiện cố định, không ghi exception/DSN/password. Route không tồn tại được xử lý trước khi truy cập DB.
- Nghiệm thu UI bắt buộc theo constitution: ảnh ở 375, 768, 1366px thuộc T010. T012 cần người khác tái lập; không thay bằng tự xác nhận.
- Generated column ảnh bìa dùng VIRTUAL (có UNIQUE), vì MySQL cấm CASCADE trên base column của STORED generated column. Đã kiểm chứng import và cascade trên DB thật. [Giới hạn generated columns](https://dev.mysql.com/doc/refman/8.4/en/create-table-generated-columns.html).

Nguồn quyết định:

- [PHP supported versions](https://www.php.net/supported-versions.php)
- [PDO_MYSQL](https://www.php.net/manual/en/ref.pdo-mysql.php)
- [MySQL 8.4 character sets](https://dev.mysql.com/doc/refman/8.4/en/charset.html)
- [MySQL CHECK constraints](https://dev.mysql.com/doc/refman/8.4/en/create-table-check-constraints.html)
