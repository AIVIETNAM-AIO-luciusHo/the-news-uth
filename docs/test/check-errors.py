"""Requires the dev server to use this checkout. Restores config in finally."""
from pathlib import Path
import sys
import urllib.error
import urllib.request

base = sys.argv[1] if len(sys.argv) > 1 else 'http://127.0.0.1:8080'
config = Path(__file__).resolve().parents[2] / 'config/config.local.php'
original = config.read_bytes()
marker = 'DO_NOT_LEAK_FOUNDATION_PASSWORD'

def check_error(label):
    try:
        urllib.request.urlopen(base + '/')
        raise AssertionError('Expected 500: ' + label)
    except urllib.error.HTTPError as error:
        body = error.read().decode('utf-8')
        assert error.code == 500 and 'Dịch vụ tạm thời gián đoạn' in body
        for forbidden in [marker, 'SQLSTATE', 'mysql:', 'Stack trace', '/app/', 'news_uth_verified']:
            assert forbidden not in body, (label, forbidden)
    print('PASS safe 500: ' + label)

try:
    config.unlink()
    check_error('missing config')
    config.write_text('<?php return [];')
    check_error('invalid config')
    config.write_text('<?php this is invalid syntax ' + marker)
    check_error('malformed PHP config')
    config.write_text("<?php return ['database' => ['host' => '127.0.0.1', 'port' => 1, 'name' => 'unavailable', 'username' => 'demo', 'password' => '" + marker + "']];")
    check_error('database unavailable')
finally:
    config.write_bytes(original)
assert urllib.request.urlopen(base + '/').code == 200
print('PASS config restored, home 200')
