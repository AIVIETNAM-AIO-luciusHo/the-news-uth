# Lộ trình triển khai The News UTH

Đây là thứ tự đề xuất từ kế hoạch đồ án sáu thành viên; không thể hiện phần trăm hoàn thành. Chủ module được ký hiệu TV01–TV06 theo kế hoạch cục bộ; nhóm cần xác nhận phân công thực tế.

| Thứ tự | Feature dự kiến | Đầu ra / điều kiện nghiệm thu | Phụ thuộc | Đầu mối đề xuất |
|---|---|---|---|---|
| 001 | project-foundation | Router, layout tối thiểu, PDO, schema/seed, hướng dẫn chạy; kiểm tra 404 và lỗi DB | Không | TV01 |
| 002 | auth-users | Login/logout, CSRF, quyền Admin/Editor, quản lý tài khoản; chặn URL/POST trái quyền | 001 | TV02 |
| 003 | categories | CRUD, slug duy nhất, bật/tắt; chặn xóa chuyên mục còn bài | 002 | TV06 |
| 004 | admin-articles | CRUD bài, validation, chủ sở hữu, draft/pending/published/archived, lọc/phân trang | 002, 003 | TV03 |
| 005 | media-editor | Editor, lọc HTML, nhiều ảnh, ảnh bìa/thứ tự, dọn file; thử MIME giả/XSS | 004 | TV04 |
| 006 | public-news | Trang chủ/chuyên mục/chi tiết/tìm kiếm, phân trang giữ bộ lọc; chỉ published | 003–005 | TV05 |
| 007 | quality-handover | Test tích hợp, responsive 375/768/1366px, CI có check thật, báo cáo/slide và demo từ máy mới | 001–006 | TV03 + TV06, TV01 tích hợp |

UI/layout do TV06 phối hợp xuyên suốt, không chờ cuối kỳ. Chủ module chịu trách nhiệm tự test; TV03 điều phối chất lượng, TV06 tổng hợp bằng chứng và báo cáo. Đánh giá đóng góp theo PR, đầu ra và kiểm thử thực tế.

## Giới hạn phạm vi
Ưu tiên đường demo: đăng nhập Admin → tạo chuyên mục → Editor tạo bài và ảnh → gửi duyệt → Admin xuất bản → người đọc tìm và xem bài. Bình luận, đăng ký độc giả, tag, thống kê và CD chỉ triển khai khi đường này ổn định.

## Các quyết định cần chốt khi lập plan
Phiên bản PHP/MySQL, TinyMCE hay CKEditor, thư viện lọc HTML, quy tắc phục vụ ảnh và môi trường demo. Deploy là tùy chọn. CODEOWNERS/Ruleset cần danh tính GitHub và quyền thực tế; chưa tạo cấu hình với tài khoản giả.
