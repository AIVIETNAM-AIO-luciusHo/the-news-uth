"""Run with Python 3: python3 docs/test/check-http.py [base URL]."""
import sys
import urllib.error
import urllib.request

base = sys.argv[1] if len(sys.argv) > 1 else 'http://127.0.0.1:8080'
cases = [
    ('/', 'GET', 200, 'Nền tảng đã sẵn sàng'),
    ('/?q=demo', 'GET', 200, 'Nền tảng đã sẵn sàng'),
    ('/admin/login', 'GET', 200, 'Trang giữ chỗ'),
    ('/missing', 'GET', 404, 'Không tìm thấy trang'),
    ('/config/config.local.php', 'GET', 404, 'Không tìm thấy trang'),
    ('/database/seed.sql', 'GET', 404, 'Không tìm thấy trang'),
    ('/admin/articles', 'GET', 404, 'Không tìm thấy trang'),
    ('/admin/login', 'POST', 405, ''),
    ('/', 'HEAD', 200, ''),
    ('/missing', 'HEAD', 404, ''),
    ('/assets/css/app.css', 'GET', 200, '.container'),
    ('/assets/images/demo-campus.svg', 'GET', 200, '<svg'),
]
for path, method, expected, content in cases:
    request = urllib.request.Request(base + path, method=method)
    try:
        response = urllib.request.urlopen(request)
    except urllib.error.HTTPError as error:
        response = error
    body = response.read().decode('utf-8')
    assert response.code == expected, (method, path, response.code)
    assert content in body, (path, 'missing content')
    assert method != 'HEAD' or body == '', (path, 'HEAD body')
    assert 'SQLSTATE' not in body and 'Stack trace' not in body
    if path == '/admin/login':
        assert '<form' not in body and 'password_hash' not in body
    print(f'PASS {method} {path}: {expected}')
