# The News UTH Constitution

## Nguyên tắc cốt lõi

### I. Bám sát yêu cầu đồ án
Xây website tin tức bằng PHP + MySQL với trang người đọc và quản trị, CRUD, đăng nhập/đăng xuất, phân quyền, phân trang, upload nhiều ảnh và editor. Ưu tiên hoàn thiện luồng chính trước bình luận, tài khoản độc giả, thống kê hoặc deploy.

### II. Kiến trúc đơn giản, rõ trách nhiệm
Dùng PHP render HTML phía server, MVC nhẹ; controller điều phối, model truy cập PDO, service xử lý nghiệp vụ, view hiển thị. Không đặt SQL trong view. Dùng cấu hình riêng cho từng máy; không đưa mật khẩu vào Git. Laravel, SPA và API riêng cần được ghi thành quyết định thay đổi kiến trúc trước khi triển khai.

### III. Bảo mật là tiêu chí nghiệm thu
Dùng prepared statements, password_hash/password_verify, kiểm tra phiên và quyền ở backend, CSRF cho thao tác thay đổi dữ liệu. Escape dữ liệu hiển thị; HTML từ editor phải được lọc theo chính sách rõ ràng. Kiểm tra MIME, dung lượng và tên ngẫu nhiên cho ảnh; cấm thực thi mã trong vùng upload. Người đọc chỉ thấy bài published; Editor không được xuất bản hay sửa bài người khác.

### IV. Đặc tả có thể kiểm chứng
Mỗi tính năng có spec.md, plan.md và tasks.md với tiêu chí Given/When/Then, trường hợp lỗi và ranh giới quyền. Task liên kết tới yêu cầu, đường dẫn đầu ra và cách kiểm tra. Không đánh dấu hoàn thành nếu chưa có đầu ra và kết quả kiểm chứng thực tế.

### V. Chất lượng và khả năng bàn giao
Kiểm tra cú pháp PHP và hành vi liên quan trước PR; thay đổi dữ liệu/phân quyền cần kiểm thử tích hợp với MySQL. UI có minh chứng ở 375, 768 và 1366px. README phải cho phép thành viên mới dựng môi trường, import schema/seed và chạy demo. Không coi php -l là kiểm thử nghiệp vụ.

## Quyết định triển khai ban đầu
PHP 8.x, MySQL, PDO, HTML/CSS/JS và Bootstrap 5 là hướng mặc định từ kế hoạch cục bộ. Phiên bản chính xác, editor và thư viện lọc HTML phải được ghi trong research.md trước khi cài phụ thuộc. Dùng PSR-12 cơ bản, thụt lề bốn dấu cách; tên bảng số nhiều, cột snake_case. Không tự nhận CI, Ruleset, CODEOWNERS hay deployment đã hoạt động khi chưa có bằng chứng.

## Quy trình phát triển
Đọc yêu cầu → clarify nếu còn mơ hồ → plan → tasks → analyze → implement → kiểm thử và review. Chia PR theo module; mô tả task, ảnh hưởng DB/config, kết quả test và ảnh UI. Giữ mã phân công gốc như T03.2 trong mô tả, phân biệt với ID task Spec Kit như T001. Không tự động tạo issue từ task khi người dùng chưa yêu cầu.

## Governance
Đây là baseline khởi tạo theo yêu cầu thiết lập của chủ repo ngày 2026-11-16, chưa phải xác nhận đồng thuận của toàn nhóm. Thay đổi nguyên tắc phải ghi lý do, ảnh hưởng và tăng phiên bản: major cho thay đổi phá vỡ nguyên tắc, minor cho bổ sung, patch cho làm rõ. Kiểm tra sự phù hợp trong mỗi plan/PR. Tuân thủ hướng dẫn trực tiếp của người dùng và AGENTS.md; không ghi đè AGENTS.md khi cập nhật ngữ cảnh agent.

**Version**: 1.0.0 | **Ratified**: 2026-11-16 (baseline của chủ repo) | **Last Amended**: 2026-11-16
