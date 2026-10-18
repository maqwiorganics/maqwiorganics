"""Replace only four reviewed owned PHP files; never touch database or host config."""
import ftplib
import hashlib
import io
import json
import os
from pathlib import Path
import ssl
import sys
import uuid

BASE = '/htdocs/wp-content/plugins/maqwi-store-core/'
LOCAL = Path('wp-content/plugins/maqwi-store-core')
BACKUP = Path('commerce-source-backup')
ORDER = ('business-setup.php', 'mail-transport.php', 'storefront-preview.php', 'maqwi-store-core.php')

def sha(data):
    return hashlib.sha256(data).hexdigest()

def read(ftp, name):
    out = io.BytesIO()
    ftp.retrbinary('RETR ' + BASE + name, out.write)
    return out.getvalue()

def replace(ftp, name, data):
    temp = '.maqwi-release-' + uuid.uuid4().hex + '.php'
    try:
        ftp.storbinary('STOR ' + BASE + temp, io.BytesIO(data))
        if read(ftp, temp) != data:
            raise RuntimeError('Temporary upload verification failed: ' + name)
        ftp.rename(BASE + temp, BASE + name)
    finally:
        names = {p.rsplit('/', 1)[-1] for p in ftp.nlst(BASE)}
        if temp in names:
            ftp.delete(BASE + temp)

def apply(ftp, package, originals):
    attempted = []
    try:
        for name in ORDER:
            data = package[name]
            if originals[name] == data:
                continue
            names = {p.rsplit('/', 1)[-1] for p in ftp.nlst(BASE)}
            if originals[name] is None:
                if name in names:
                    raise RuntimeError('New source appeared during release: ' + name)
            elif read(ftp, name) != originals[name]:
                raise RuntimeError('Source changed during release: ' + name)
            attempted.append(name)
            replace(ftp, name, data)
            if read(ftp, name) != data:
                raise RuntimeError('Installed source verification failed: ' + name)
    except Exception as error:
        failures = []
        for name in reversed(attempted):
            try:
                if originals[name] is None:
                    names = {p.rsplit('/', 1)[-1] for p in ftp.nlst(BASE)}
                    if name in names:
                        if read(ftp, name) != package[name]:
                            raise RuntimeError('New source changed; preserved for review')
                        ftp.delete(BASE + name)
                else:
                    replace(ftp, name, originals[name])
                    if read(ftp, name) != originals[name]:
                        raise RuntimeError('Recovery verification failed')
            except Exception:
                failures.append(name)
        if failures:
            raise RuntimeError('Source recovery needs review: ' + ', '.join(failures)) from error
        raise

def rollback_installed(ftp, package):
    previous = json.loads((BACKUP / 'originals.json').read_text())
    if set(previous) != set(ORDER):
        raise RuntimeError('Unexpected recovery scope')
    for name in ORDER:
        if read(ftp, name) != package[name]:
            raise RuntimeError('Installed source changed; refusing to overwrite: ' + name)
        if previous[name] is not None and sha((BACKUP / name).read_bytes()) != previous[name]:
            raise RuntimeError('Recovery bytes failed integrity check: ' + name)
    for name in reversed(ORDER):
        if previous[name] is None:
            ftp.delete(BASE + name)
        else:
            data = (BACKUP / name).read_bytes()
            replace(ftp, name, data)
            if read(ftp, name) != data:
                raise RuntimeError('Recovery verification failed: ' + name)
    names = {p.rsplit('/', 1)[-1] for p in ftp.nlst(BASE)}
    if any(previous[n] is None and n in names for n in ORDER):
        raise RuntimeError('New module removal could not be verified')

def main(phase):
    if phase not in ('prepare', 'apply', 'rollback'):
        raise ValueError('Use prepare, apply or rollback')
    release = json.loads(Path('config/commerce-source-release.json').read_text())
    package = {name: (LOCAL / name).read_bytes() for name in ORDER}
    if set(release['package']) != set(ORDER) or any(sha(data) != release['package'][name] for name, data in package.items()):
        raise RuntimeError('Package differs from the tested release')
    baseline = release['baseline']
    if set(baseline) != {'maqwi-store-core.php', 'storefront-preview.php', 'catalogue-release.php', 'storefront-copy.php', 'photography/manifest.json'}:
        raise RuntimeError('Unexpected baseline scope')
    ftp = ftplib.FTP_TLS(context=ssl.create_default_context(), timeout=35)
    try:
        ftp.connect(os.environ['HOST'], 21)
        ftp.login(os.environ['USERNAME'], os.environ['PASSWORD'])
        ftp.prot_p()
        if phase == 'rollback':
            rollback_installed(ftp, package)
            print('Previous owned source restored and verified; existing backups preserved.')
            return
        names = {p.rsplit('/', 1)[-1] for p in ftp.nlst(BASE)}
        if {'business-setup.php', 'mail-transport.php'} & names:
            raise RuntimeError('New module already exists; review before another release')
        originals = {}
        for name, expected in baseline.items():
            data = read(ftp, name)
            if sha(data) != expected:
                raise RuntimeError('Live source changed: ' + name)
            if name in ORDER:
                originals[name] = data
        originals.update({'business-setup.php': None, 'mail-transport.php': None})
        if phase == 'prepare':
            BACKUP.mkdir(exist_ok=True)
            for name, data in originals.items():
                if data is not None:
                    (BACKUP / name).write_bytes(data)
            (BACKUP / 'originals.json').write_text(json.dumps({n: sha(d) if d is not None else None for n, d in originals.items()}, indent=2))
            print('Verified current scoped source. No remote writes.')
        else:
            previous = json.loads((BACKUP / 'originals.json').read_text())
            if previous != {n: sha(d) if d is not None else None for n, d in originals.items()}:
                raise RuntimeError('Prepared snapshot no longer matches live source')
            for name, data in originals.items():
                if data is not None and (BACKUP / name).read_bytes() != data:
                    raise RuntimeError('Original source snapshot differs: ' + name)
            apply(ftp, package, originals)
            print('Scoped owned source installed and read back exactly. No configuration request, database migration, payment or launch action performed.')
    finally:
        ftp.close()

if __name__ == '__main__':
    main(sys.argv[1])
