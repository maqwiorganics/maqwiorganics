"""Scoped FTPS deployment. A reviewed remote hash baseline is mandatory."""
import ftplib
import hashlib
import io
import json
import os
from pathlib import Path
import ssl
import sys

BASE = '/htdocs/wp-content/plugins/maqwi-store-core'
LOCAL = Path('wp-content/plugins/maqwi-store-core')
BACKUP = Path('deploy-backup')


def digest(data):
    return hashlib.sha256(data).hexdigest()


def read_remote(ftp, name):
    data = io.BytesIO()
    ftp.retrbinary('RETR ' + BASE + '/' + name, data.write)
    return data.getvalue()


def apply_files(ftp, package, originals):
    # Include the current file BEFORE STOR: a failed STOR can truncate it.
    attempted = []
    try:
        for name, data in package.items():
            if data == originals[name]:
                continue
            attempted.append(name)
            ftp.storbinary('STOR ' + BASE + '/' + name, io.BytesIO(data))
            if read_remote(ftp, name) != data:
                raise RuntimeError('Read-back mismatch: ' + name)
    except Exception as error:
        failed = []
        for name in reversed(attempted):
            try:
                ftp.storbinary('STOR ' + BASE + '/' + name, io.BytesIO(originals[name]))
                if read_remote(ftp, name) != originals[name]:
                    raise RuntimeError('Rollback verification failed')
            except Exception:
                failed.append(name)
        if failed:
            raise RuntimeError('Recovery required for: ' + ', '.join(failed)) from error
        raise


def main(phase):
    if phase not in ('prepare', 'apply'):
        raise ValueError('Expected prepare or apply')
    baseline = json.loads(Path('config/remote-plugin-sha256.json').read_text())
    package = {}
    for file in LOCAL.rglob('*'):
        if file.is_symlink():
            raise ValueError('Symlink rejected')
        if not file.is_file():
            continue
        name = file.relative_to(LOCAL).as_posix()
        if file.suffix not in ('.php', '.json', '.webp') or file.stat().st_size > 1_000_000:
            raise ValueError('Unexpected package file: ' + name)
        package[name] = file.read_bytes()
    if len(package) < 5 or 'maqwi-store-core.php' not in package:
        raise ValueError('Incomplete source package')
    if set(package) != set(baseline):
        raise ValueError('Package must match reviewed remote baseline; additions need separate review')
    ftp = ftplib.FTP_TLS(context=ssl.create_default_context(), timeout=40)
    try:
        ftp.connect(os.environ['HOST'], 21)
        ftp.login(os.environ['USERNAME'], os.environ['PASSWORD'])
        ftp.prot_p()
        originals = {}
        for name in package:
            original = read_remote(ftp, name)  # Missing/denied files fail closed.
            if digest(original) != baseline[name]:
                raise RuntimeError('Remote source changed: ' + name)
            originals[name] = original
        if phase == 'prepare':
            for name, data in originals.items():
                path = BACKUP / name
                path.parent.mkdir(parents=True, exist_ok=True)
                path.write_bytes(data)
            (BACKUP / 'manifest.json').write_text(json.dumps(baseline, indent=2))
            print('Verified source backup prepared; no remote writes.')
        else:
            for name, data in originals.items():
                if (BACKUP / name).read_bytes() != data:
                    raise RuntimeError('Backup mismatch: ' + name)
            apply_files(ftp, package, originals)
            print('All scoped source writes verified. No launch or payment setting changed.')
    finally:
        ftp.close()


if __name__ == '__main__':
    main(sys.argv[1])
