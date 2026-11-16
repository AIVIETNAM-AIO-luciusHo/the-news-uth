# Implementation Plan: Nền tảng ứng dụng

**Feature**: 001-project-foundation | **Status**: Draft | **Date**: 2026-11-16
**Spec**: [spec.md](spec.md)

## Summary
Dựng PHP SSR/MVC nhẹ, PDO/MySQL và dữ liệu demo để các module sau phát triển trên cùng nền. Không bổ sung framework hoặc REST API riêng.

## Technical Context
PHP 8.x và MySQL; phiên bản cụ thể phải được xác minh trên môi trường nhóm và ghi ở research.md trước triển khai. Không cần Node/build frontend ở giai đoạn này. Composer chỉ thêm khi có phụ thuộc được giải thích và khóa phiên bản.

## Constitution Check
- Phạm vi PHP/MySQL và không thêm chức năng ngoài nền tảng: phù hợp.
- Tách MVC, PDO, config riêng: được thiết kế; chưa xác minh bằng mã.
- Không có endpoint quản trị thay đổi dữ liệu khi chưa có auth: bắt buộc.
- Có tiêu chí HTTP/DB, syntax và hướng dẫn tái lập: có trong tasks; chưa chạy.
- AGENTS.md: giữ nguyên, không chạy thao tác cập nhật ghi đè file này.

## Project Structure dự kiến
```text
app/core/{Router,Database,View}.php
app/controllers/{HomeController,AuthController}.php
app/models/SystemStatus.php
app/services/ (dành cho module nghiệp vụ sau)
views/layouts/public.php
views/public/home.php
views/auth/login.php
views/errors/{404,500}.php
public/index.php
public/assets/{css,js}/
config/config.example.php
database/{schema,seed}.sql
docs/test/foundation.md
```

## Các bước
1. Research môi trường và chốt phiên bản; thiết kế schema theo data-model.md.
2. Tạo config mẫu, PDO và schema/seed, không dùng credential thật.
3. Tạo router/layout/controller; xử lý 404 và lỗi DB an toàn.
4. Viết README, smoke test HTTP/DB, ghi bằng chứng và review.

## Testing Strategy
Chạy php -l trên toàn bộ PHP; import schema/seed trên database thử riêng; kiểm tra khóa ngoại và hash; smoke test `/`, `/admin/login`, URL không tồn tại, lỗi config/DB. Không dùng database thật để thử reset. Đầu ra kiểm thử ở docs/test/foundation.md. Không đặt ngưỡng coverage khi chưa có framework.

Theo constitution V, T010 gồm kiểm tra responsive và ảnh UI ở 375, 768, 1366px cho các trang mẫu, lưu tại docs/test/screenshots/. T012 cần thành viên khác tái lập từ checkout sạch và ghi kết quả; kiểm tra tự động của agent không thay thế bước này.

## Risks & Decisions
Đảm bảo web document root là public/; không phục vụ config/database trực tiếp. Chọn cách rewrite/router phù hợp local server và ghi lệnh thật sau kiểm chứng. Việc import seed lặp lại phải có chính sách rõ ràng; không âm thầm xóa dữ liệu cũ. Feature sau bổ sung auth trước mọi CRUD.
