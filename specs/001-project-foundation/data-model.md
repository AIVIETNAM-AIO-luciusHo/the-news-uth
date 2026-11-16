# Data Model: Nền tảng

Quyết định T002: MySQL 8.4, InnoDB, utf8mb4_0900_ai_ci. Mọi id/FK là BIGINT UNSIGNED; tên/email/slug/path tối đa 255 ký tự (name 120), hash VARCHAR(255); summary TEXT, content MEDIUMTEXT; is_active/is_cover BOOLEAN kèm CHECK 0/1; sort_order INT UNSIGNED. Timestamp DATETIME theo UTC, created_at/updated_at mặc định CURRENT_TIMESTAMP, updated_at tự cập nhật. published_at nullable; published phải có thời điểm xuất bản. Role/status dùng ENUM.

| Bảng | Trường chính dự kiến | Ràng buộc |
|---|---|---|
| users | id, name, email, password_hash, role, is_active, created_at, updated_at | email duy nhất; role admin/editor; lưu hash |
| categories | id, name, slug, is_active, created_at, updated_at | slug duy nhất; chặn xóa khi có bài |
| articles | id, author_id, category_id, title, slug, summary, content, status, published_at, created_at, updated_at | FK users/categories; slug duy nhất; trạng thái hợp lệ |
| article_images | id, article_id, path, alt_text, is_cover, sort_order | FK articles; đường dẫn tương đối; thứ tự xác định |

Index: email/slug UNIQUE; articles(author_id), articles(category_id, status, published_at), articles(status, published_at); article_images(article_id, sort_order, id). FK tác giả/chuyên mục ON DELETE RESTRICT, ảnh ON DELETE CASCADE. Chỉ một ảnh bìa được bảo đảm bằng cột generated cover_article_id nullable có UNIQUE; nhất quán xóa file thuộc feature media. Không cascade xóa tài khoản/chuyên mục khiến mất bài. Dữ liệu seed tổng hợp gồm 2 users, 2 categories, 4 articles (mỗi trạng thái một bài), 2 article_images tham chiếu SVG demo có thật. Import schema/seed chỉ một lần trên DB rỗng, xung đột phải báo lỗi; seed trong transaction và không tự reset dữ liệu.
