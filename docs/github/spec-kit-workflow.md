# Làm việc với GitHub Spec Kit

## Trạng thái và nguồn
Repo đã có hạ tầng Spec Kit từ Specify CLI **1.2.3**, script Bash và bộ tích hợp Codex dạng skills. Nguồn chính thức: https://github.com/github/spec-kit và https://github.github.com/spec-kit/overview/core.html. CLI có sẵn trên máy hiện tại; thành viên khác cần tự cài CLI theo tài liệu chính thức. Không cần CLI để đọc đặc tả.

AGENTS.md được giữ nguyên. Tài liệu trong docs-local/ là đầu vào cục bộ chưa được Git theo dõi; các quy tắc cần thiết đã được tóm tắt trong constitution và roadmap để thành viên khác không phụ thuộc thư mục này. Bộ specs/001-project-foundation là bản nháp khởi đầu, chưa có mã ứng dụng hay kết quả test nghiệp vụ.

## Bắt đầu ngay
Mở lại phiên Codex trong repo sau khi cài skills. Nhập các lệnh bên dưới vào **hội thoại Codex**, không chạy trong terminal. CLI 1.2.3 tạo skills với tên dấu gạch ngang:

```text
$speckit-analyze Kiểm tra specs/001-project-foundation theo constitution; báo thiếu sót trước khi code.
$speckit-implement Triển khai specs/001-project-foundation theo tasks.md, kiểm tra từng bước và chỉ đánh dấu task khi có bằng chứng.
```

Nếu agent chưa nhận diện skills, mở phiên mới; có thể yêu cầu đọc trực tiếp `.agents/skills/speckit-analyze/SKILL.md`. Với nhiều feature, chọn rõ thư mục bằng biến môi trường khi khởi chạy agent:

```bash
export SPECIFY_FEATURE_DIRECTORY=specs/001-project-foundation
```

Bộ Spec Kit này không cần tự tạo hoặc chuyển nhánh để đọc bộ đặc tả. Khi triển khai, dùng nhánh riêng, ví dụ `001-project-foundation`; không chạy script tạo feature mới để mở lại feature đã có.

## Chu trình cho tính năng tiếp theo

1. Đọc constitution và roadmap, chọn một module có đầu ra vừa sức.
2. `$speckit-specify <mô tả người dùng, quyền, dữ liệu, tiêu chí thành công>`.
3. `$speckit-clarify` để làm rõ yêu cầu; ghi quyết định và giả định.
4. `$speckit-plan <ràng buộc PHP/MySQL và phạm vi module>` để tạo phương án kỹ thuật.
5. `$speckit-checklist` rà soát chất lượng yêu cầu, rồi `$speckit-tasks` chia công việc.
6. `$speckit-analyze` kiểm tra tính nhất quán; xử lý phát hiện nghiêm trọng.
7. `$speckit-implement`, chạy kiểm thử, mở PR với bằng chứng.
8. `$speckit-converge` nếu cần đối chiếu mã thực tế với yêu cầu còn thiếu.

Giữ một feature đang hoạt động cho mỗi phiên; cập nhật SPECIFY_FEATURE_DIRECTORY khi đổi feature. Không chạy lại specify để thay thế đặc tả đã được nhóm sửa mà chưa đọc nội dung.

## Kiểm tra hạ tầng bằng terminal

```bash
specify --version
SPECIFY_FEATURE_DIRECTORY=specs/001-project-foundation bash .specify/scripts/bash/check-prerequisites.sh --json --require-spec --require-tasks --include-tasks
```

Kết quả thành công chỉ xác nhận đầu vào workflow tồn tại. Chưa chứng minh PHP/MySQL hoạt động. Script và template chính thức nằm trong `.specify/`; không tự chỉnh template nếu chỉ cần thay nội dung feature.

## Hoàn thành một PR
Liên kết yêu cầu và task; liệt kê file/DB/config thay đổi, lệnh test và kết quả thật, lỗi còn lại; đính ảnh cho UI. Không đánh dấu pass cho kiểm tra chưa chạy. Chỉ ghi CI/Ruleset đã bật sau khi kiểm chứng trên GitHub; việc thiết lập local không thay đổi Settings của repo từ xa.
