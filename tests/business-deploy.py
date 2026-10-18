import importlib.util
from pathlib import Path
import unittest

spec = importlib.util.spec_from_file_location('deploy', Path('.github/scripts/deploy_owned_commerce.py'))
deploy = importlib.util.module_from_spec(spec)
spec.loader.exec_module(deploy)

class FTP:
    def __init__(self, files, fail=None):
        self.files = dict(files)
        self.fail = fail
    def storbinary(self, command, stream):
        self.files[command[5:]] = stream.read()
    def retrbinary(self, command, callback):
        callback(self.files[command[5:]])
    def rename(self, src, dest):
        if dest.endswith('/' + str(self.fail)):
            self.fail = None
            raise OSError('Injected rename failure')
        self.files[dest] = self.files.pop(src)
    def nlst(self, base):
        return [p for p in self.files if p.startswith(base)]
    def delete(self, path):
        del self.files[path]

class Recovery(unittest.TestCase):
    def run_case(self, fail):
        originals = {n: (b'old-' + n.encode() if n in ('storefront-preview.php', 'maqwi-store-core.php') else None) for n in deploy.ORDER}
        files = {deploy.BASE+n: d for n,d in originals.items() if d is not None}
        files[deploy.BASE+'preserved-backup.zip'] = b'untouched'
        ftp = FTP(files, fail)
        package = {n: b'new-' + n.encode() for n in deploy.ORDER}
        if fail:
            with self.assertRaises(OSError): deploy.apply(ftp, package, originals)
            self.assertEqual(files, ftp.files)
        else:
            deploy.apply(ftp, package, originals)
            for name in deploy.ORDER: self.assertEqual(ftp.files[deploy.BASE+name], package[name])
            self.assertEqual(ftp.files[deploy.BASE+'preserved-backup.zip'], b'untouched')
    def test_success(self): self.run_case(None)
    def test_failure_before_core_commit_restores_existing_and_removes_only_new_modules(self): self.run_case('maqwi-store-core.php')
    def test_failure_during_second_new_module_restores_absence(self): self.run_case('mail-transport.php')

if __name__ == '__main__': unittest.main()
