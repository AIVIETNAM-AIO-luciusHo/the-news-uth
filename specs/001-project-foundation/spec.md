# Feature Specification: Nền tảng ứng dụng

**Feature**: 001-project-foundation | **Created**: 2026-11-16 | **Status**: Draft
**Nguồn**: Yêu cầu đồ án và đề xuất kiến trúc PHP/MySQL trong docs-local/, được tóm tắt độc lập tại đây.

## User Scenarios & Testing

### US1 — Thành viên dựng được môi trường (P1)
Là thành viên nhóm, tôi cần làm theo README để dựng ứng dụng từ checkout sạch và nhập dữ liệu mẫu mà không cần thông tin bí mật của người khác.
- Given máy có các phiên bản đã ghi trong README, When tạo config cục bộ và import schema/seed, Then ứng dụng kết nối được MySQL và hiển thị trang chủ mẫu.
- Given config thiếu hoặc MySQL ngừng chạy, When truy cập ứng dụng, Then trả thông báo lỗi an toàn, không lộ DSN, mật khẩu hay stack trace.

### US2 — Thành viên phát triển module nhất quán (P1)
- Given ứng dụng đang chạy, When mở `/`, Then trả HTTP 200 với layout public dùng chung.
- Given khung quản trị chưa có auth, When mở `/admin/login`, Then chỉ thấy trang giữ chỗ không có hành động quản trị hay dữ liệu nhạy cảm.
- Given đường dẫn không tồn tại, When truy cập, Then trả HTTP 404 cùng trang thông báo thân thiện.

### US3 — Thành viên có dữ liệu demo nhất quán (P2)
- Given database rỗng, When import schema rồi seed theo README, Then có dữ liệu tổng hợp cho users, categories, articles và article_images thỏa khóa ngoại.
- Given kiểm tra tài khoản demo, Then mật khẩu trong DB là hash, không có dữ liệu người dùng thật.

## Requirements
- FR-001: Cung cấp entry point public, router và tách controller/model/service/view.
- FR-002: Kết nối MySQL qua PDO, utf8mb4, exception mode, prepared statements.
- FR-003: Cung cấp schema và seed; tài liệu giải thích thứ tự import và cảnh báo thao tác reset dữ liệu.
- FR-004: Dùng config mẫu không có bí mật; bỏ qua config cục bộ trong Git.
- FR-005: Hỗ trợ trang mẫu `/`, `/admin/login` và lỗi 404; không cung cấp CRUD chưa bảo vệ.
- FR-006: README ghi phiên bản, extension PHP, document root, lệnh chạy và cách kiểm tra.
- FR-007: Kiểm tra cú pháp và smoke test HTTP/DB có kết quả ghi lại.

## Key Entities
Users có vai trò admin/editor; categories có slug và trạng thái hiển thị; articles liên kết tác giả/chuyên mục và có draft/pending/published/archived; article_images liên kết bài và thông tin ảnh. Ràng buộc chi tiết ở data-model.md.

## Success Criteria
- SC-001: Một thành viên khác dựng được demo từ checkout sạch bằng README và dữ liệu tổng hợp.
- SC-002: Ba route trên trả đúng mã HTTP trong smoke test.
- SC-003: Schema import thành công, dữ liệu seed hợp lệ và thông tin bí mật không xuất hiện trong response lỗi.

## Assumptions & Scope
Đây là nền tảng, chưa bao gồm đăng nhập thực, CRUD, upload, editor hay giao diện hoàn thiện. Các kiểm tra chưa chạy; toàn bộ tiêu chí đang chờ thực hiện. Phiên bản runtime cụ thể được chọn và ghi lại ở bước research trước code.
