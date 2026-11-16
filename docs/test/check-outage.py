"""Run only after stopping the disposable MySQL server; restart it afterwards."""
import sys
import urllib.error
import urllib.request

base = sys.argv[1] if len(sys.argv) > 1 else 'http://127.0.0.1:8080'
for path, expected in [('/', 500), ('/admin/login', 200), ('/missing', 404)]:
    try:
        response = urllib.request.urlopen(base + path)
    except urllib.error.HTTPError as error:
        response = error
    body = response.read().decode('utf-8')
    assert response.code == expected, (path, response.code)
    for forbidden in ['SQLSTATE', 'Stack trace', 'mysql:', 'password', '/app/']:
        assert forbidden not in body, forbidden
    print(f'PASS DB stopped: {path} {expected}')
