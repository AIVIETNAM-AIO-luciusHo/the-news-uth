# Tasks: Nền tảng ứng dụng

**Status**: Đã triển khai và kiểm chứng kỹ thuật T001–T011; T012 chờ thành viên khác tái lập. T001… là ID nội bộ feature, không thay mã phân công T01.1/T01.2 của kế hoạch nhóm.

## Phase 1 — Setup và research
- [x] T001 Ghi phiên bản PHP/MySQL, extension cần dùng và lệnh local server đã chọn trong specs/001-project-foundation/research.md (FR-006).
- [x] T002 Rà soát kiểu dữ liệu, FK/index, seed và quyết định cập nhật specs/001-project-foundation/data-model.md (FR-003).

## Phase 2 — US1: Môi trường và database
- [x] T003 Tạo config/config.example.php, cơ chế đọc config/config.local.php và PDO tại app/core/Database.php; không lộ bí mật (FR-002, FR-004).
- [x] T004 Viết database/schema.sql theo data-model.md và database/seed.sql với tài khoản hash/dữ liệu tổng hợp (FR-003).
- [x] T005 Import trên MySQL thử riêng; ghi lệnh, kết quả FK/seed và chính sách import lặp lại vào docs/test/foundation.md (FR-003, FR-007).

## Phase 3 — US2: Router và giao diện mẫu
- [x] T006 Tạo public/index.php và app/core/Router.php, phân tuyến và HTTP status đúng (FR-001, FR-005).
- [x] T007 Tạo HomeController/AuthController, layout public và trang home/login giữ chỗ tại views/; không có hành động quản trị (FR-005).
- [x] T008 Tạo views/errors/404.php và views/errors/500.php; thông báo lỗi an toàn và log phía server không chứa password (FR-002, FR-005).

## Phase 4 — US3 và bàn giao
- [x] T009 Kiểm tra hash tài khoản và các liên kết seed bằng truy vấn trên DB thử; bổ sung kết quả vào docs/test/foundation.md (FR-003, FR-007).
- [x] T010 Chạy php -l cho toàn bộ PHP; kiểm tra HTTP 200/404 và lỗi DB/config không lộ bí mật; kiểm tra UI và chụp ảnh 375/768/1366px tại docs/test/screenshots/ theo constitution V; ghi kết quả thực tế (FR-007).
- [x] T011 Cập nhật README.md với phiên bản, tạo config, import schema/seed, document root và lệnh chạy đã kiểm chứng (FR-006).
- [ ] T012 Nhờ thành viên tái lập từ checkout sạch; ghi kết quả, lỗi còn lại và thông tin bàn giao ở docs/test/foundation.md (SC-001–003).

## Dependencies & Definition of Done
T001–T002 trước T003–T004; T005 sau T003–T004; T006 trước T007–T008; T009 sau seed; T010 sau các phần code; T011–T012 chốt bàn giao. Chỉ đánh dấu task khi đã tạo đầu ra và kiểm chứng. Không ghi pass khi thiếu PHP/MySQL; ghi rõ chưa chạy và nguyên nhân. PR cần ánh xạ yêu cầu, kết quả test và reviewer.
