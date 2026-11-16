-- Synthetic local demo only. Password: FoundationDemo!2026 (bcrypt cost 12).
-- Never use these accounts in production. Import once; abort on first SQL error.
SET NAMES utf8mb4;
SET time_zone = '+00:00';
START TRANSACTION;

INSERT INTO users (id, name, email, password_hash, role) VALUES
    (1, 'Quản trị demo', 'admin@example.test', '$2y$12$/t8YE3xyiKJvQ4LlnyU4GOStUA4/fDYvIDtcwWgiiSyCPzmPi0hRi', 'admin'),
    (2, 'Biên tập demo', 'editor@example.test', '$2y$12$/t8YE3xyiKJvQ4LlnyU4GOStUA4/fDYvIDtcwWgiiSyCPzmPi0hRi', 'editor');

INSERT INTO categories (id, name, slug) VALUES
    (1, 'Đời sống sinh viên', 'doi-song-sinh-vien'),
    (2, 'Học tập', 'hoc-tap');

INSERT INTO articles (id, author_id, category_id, title, slug, summary, content, status, published_at) VALUES
    (1, 1, 1, 'Bản tin sinh viên minh họa', 'ban-tin-demo', 'Dữ liệu tổng hợp để kiểm thử.', 'Nội dung minh họa, không phải thông tin chính thức.', 'published', '2026-11-16 00:00:00'),
    (2, 2, 2, 'Bản nháp minh họa', 'ban-nhap-demo', 'Bản nháp tổng hợp.', 'Nội dung bản nháp.', 'draft', NULL),
    (3, 2, 1, 'Bài chờ duyệt minh họa', 'cho-duyet-demo', 'Bài chờ duyệt tổng hợp.', 'Nội dung chờ duyệt.', 'pending', NULL),
    (4, 1, 2, 'Bài lưu trữ minh họa', 'luu-tru-demo', 'Bài lưu trữ tổng hợp.', 'Nội dung lưu trữ.', 'archived', '2026-11-15 00:00:00');

INSERT INTO article_images (id, article_id, path, alt_text, is_cover, sort_order) VALUES
    (1, 1, 'assets/images/demo-campus.svg', 'Minh họa khuôn viên', 1, 0),
    (2, 1, 'assets/images/demo-library.svg', 'Minh họa thư viện', 0, 1);

COMMIT;
