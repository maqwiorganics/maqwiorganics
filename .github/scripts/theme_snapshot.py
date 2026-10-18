import ftplib,ssl,os,io,json,hashlib,subprocess,pathlib
root='wp-content/themes/maqwi-organics/'
ftp=ftplib.FTP_TLS(context=ssl.create_default_context(),timeout=35)
ftp.connect(os.environ['HOST'],21)
ftp.login(os.environ['USERNAME'],os.environ['PASSWORD']);ftp.prot_p()
pathlib.Path('theme-backup').mkdir(exist_ok=True)
manifest={}
for name in ['style.css','functions.php']:
    out=io.BytesIO();ftp.retrbinary('RETR /htdocs/'+root+name,out.write);data=out.getvalue()
    pathlib.Path('theme-backup',name).write_bytes(data)
    sha=hashlib.sha256(data).hexdigest()
    matches=[]
    for c in subprocess.check_output(['git','rev-list','--all','--',root+name],text=True).splitlines()[:80]:
        old=subprocess.check_output(['git','show',c+':'+root+name])
        if old==data: matches.append(c)
    manifest[name]=sha
    print(json.dumps({'file':name,'sha256':sha,'matching_commits':matches}))
pathlib.Path('theme-backup/manifest.json').write_text(json.dumps(manifest,indent=2))
ftp.quit()
