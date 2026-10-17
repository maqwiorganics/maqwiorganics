"""Exercise failed/partial upload and failed read-back recovery without a server."""
import importlib.util
from pathlib import Path
import unittest

spec = importlib.util.spec_from_file_location('deploy', Path(__file__).parents[1] / '.github/scripts/deploy_plugin.py')
deploy = importlib.util.module_from_spec(spec)
spec.loader.exec_module(deploy)


class FTP:
    def __init__(self, failure):
        self.files = {'a.php': b'old-a', 'b.php': b'old-b'}
        self.failure = failure
        self.failed = False

    def storbinary(self, cmd, stream):
        name = cmd.rsplit('/', 1)[1]
        data = stream.read()
        self.files[name] = data
        if name == 'b.php' and not self.failed:
            self.failed = True
            if self.failure == 'upload':
                self.files[name] = b'partial'
                raise OSError('Connection lost')
            if self.failure == 'readback':
                self.files[name] = b'corrupt'

    def retrbinary(self, cmd, callback):
        callback(self.files[cmd.rsplit('/', 1)[1]])


class Recovery(unittest.TestCase):
    def test_partial_upload_and_readback_failure_restore_every_attempted_file(self):
        for mode in ('upload', 'readback'):
            with self.subTest(mode=mode):
                ftp = FTP(mode)
                before = dict(ftp.files)
                with self.assertRaises((OSError, RuntimeError)):
                    deploy.apply_files(ftp, {'a.php': b'new-a', 'b.php': b'new-b'}, before)
                self.assertEqual(before, ftp.files)

    def test_success(self):
        ftp = FTP(None)
        package = {'a.php': b'new-a', 'b.php': b'new-b'}
        deploy.apply_files(ftp, package, dict(ftp.files))
        self.assertEqual(package, ftp.files)


if __name__ == '__main__':
    unittest.main()
