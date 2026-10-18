"""Deploy only the two reviewed hero theme files, with verified restoration."""
import ftplib,ssl,os,io,json,hashlib,pathlib,sys,secrets
ROOT='/htdocs/wp-content/themes/maqwi-organics/'
LOCAL=pathlib.Path('wp-content/themes/maqwi-organics')
BACKUP=pathlib.Path('theme-backup')
FILES=('functions.php','style.css')
def digest(data): return hashlib.sha256(data).hexdigest()
def read(ftp,path):
    data=io.BytesIO();ftp.retrbinary('RETR '+path,data.write);return data.getvalue()
def replace(ftp,name,data):
    tmp=ROOT+'.release-'+secrets.token_hex(8)+pathlib.Path(name).suffix
    try:
        ftp.storbinary('STOR '+tmp,io.BytesIO(data))
        assert read(ftp,tmp)==data,'Temporary upload mismatch'
        ftp.rename(tmp,ROOT+name)
        assert read(ftp,ROOT+name)==data,'Installed source mismatch'
    finally:
        try: ftp.delete(tmp)
        except ftplib.error_perm as e:
            if not str(e).startswith('550'): raise
def main(phase):
    assert phase in ('prepare','apply')
    baseline=json.loads(pathlib.Path('config/theme-release-baseline.json').read_text())
    assert set(baseline)==set(FILES)
    ftp=ftplib.FTP_TLS(context=ssl.create_default_context(),timeout=35)
    try:
        ftp.connect(os.environ['HOST'],21);ftp.login(os.environ['USERNAME'],os.environ['PASSWORD']);ftp.prot_p()
        originals={}
        for name in FILES:
            data=read(ftp,ROOT+name)
            assert digest(data)==baseline[name],'Live baseline changed: '+name
            originals[name]=data
        if phase=='prepare':
            BACKUP.mkdir(exist_ok=True)
            for name,data in originals.items(): (BACKUP/name).write_bytes(data)
            (BACKUP/'manifest.json').write_text(json.dumps(baseline,indent=2))
            print('Verified originals prepared; no remote writes.')
            return
        for name,data in originals.items(): assert (BACKUP/name).read_bytes()==data,'Backup mismatch'
        attempted=[]
        try:
            for name in FILES:
                data=(LOCAL/name).read_bytes()
                attempted.append(name)
                replace(ftp,name,data)
                print('Published and verified '+name)
        except Exception:
            failures=[]
            for name in reversed(attempted):
                try: replace(ftp,name,originals[name])
                except Exception: failures.append(name)
            if failures: raise RuntimeError('Recovery required: '+','.join(failures))
            raise
        print('Hero release complete. Ordering and payment settings unchanged.')
    finally: ftp.close()
if __name__=='__main__': main(sys.argv[1])
