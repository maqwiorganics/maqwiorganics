"""Deploy only the reviewed glass CSS block to the existing live Maqwi theme.
Read and back up first; fail closed on unexpected contents.
"""
import ftplib
import hashlib
import io
import json
import os
from pathlib import Path
import re
import ssl
import sys

REMOTE = "/htdocs/wp-content/themes/maqwi-organics/style.css"
LOCAL = Path("wp-content/themes/maqwi-organics/style.css")
BACKUP = Path("live-theme-backup")
START = b"/* MAQWI-LIVE-COMMERCE-20261018-START */"
END = b"/* MAQWI-LIVE-COMMERCE-20261018-END */"


def digest(value):
    return hashlib.sha256(value).hexdigest()


def connect():
    ftp = ftplib.FTP_TLS(context=ssl.create_default_context(), timeout=40)
    ftp.connect(os.environ["FTP_HOST"], 21)
    ftp.login(os.environ["FTP_USER"], os.environ["FTP_PASS"])
    ftp.prot_p()
    return ftp


def read(ftp):
    parts = []
    ftp.retrbinary("RETR " + REMOTE, parts.append)
    return b"".join(parts)


def prepare():
    source = LOCAL.read_bytes()
    if source.count(START) != 1 or source.count(END) != 1:
        raise RuntimeError("Reviewed design marker mismatch")
    css = source[source.index(START):source.index(END)+len(END)]
    if not 2000 < len(css) < 20000:
        raise RuntimeError("Unexpected design block size")
    if source.count(b"{") != source.count(b"}"):
        raise RuntimeError("Source CSS unbalanced")
    if b"--mq-green:#2F5D3A" not in source or b"--mq-cream:#F7F2E7" not in source:
        raise RuntimeError("Brand palette mismatch")
    ftp = connect()
    try:
        original = read(ftp)
    finally:
        ftp.close()
    if b"Theme Name: Maqwi Organics" not in original[:500] or b"Template: kadence" not in original[:500]:
        raise RuntimeError("Refusing to edit an unrecognized theme")
    if not 10000 < len(original) < 200000 or original.count(b"{") != original.count(b"}"):
        raise RuntimeError("Unexpected live theme CSS; manual inspection required")
    if START in original or END in original:
        raise RuntimeError("Design release already present or partially present; fail closed")
    changed, count = re.subn(rb"(?m)^Version:\s*[0-9.]+", b"Version: 0.12.1", original, count=1)
    if count != 1:
        raise RuntimeError("Theme version header missing")
    prepared = changed.rstrip() + b"\n\n" + css + b"\n"
    if prepared.count(b"{") != prepared.count(b"}"):
        raise RuntimeError("Prepared CSS unbalanced")
    BACKUP.mkdir(exist_ok=True)
    (BACKUP / "style.css").write_bytes(original)
    (BACKUP / "prepared-style.css").write_bytes(prepared)
    (BACKUP / "manifest.json").write_text(json.dumps({
        "original_sha256": digest(original),
        "prepared_sha256": digest(prepared),
        "remote": REMOTE,
        "scope": "Maqwi theme CSS only, no database or checkout modifications"
    }, indent=2))
    print("Remote stylesheet backed up locally; upload artifact before running apply. No remote write.")


def apply():
    info = json.loads((BACKUP / "manifest.json").read_text())
    original = (BACKUP / "style.css").read_bytes()
    prepared = (BACKUP / "prepared-style.css").read_bytes()
    if digest(original) != info["original_sha256"] or digest(prepared) != info["prepared_sha256"]:
        raise RuntimeError("Local backup integrity check failed")
    ftp = connect()
    attempted = False
    try:
        if digest(read(ftp)) != info["original_sha256"]:
            raise RuntimeError("Live CSS changed since backup; no write")
        try:
            attempted = True
            ftp.storbinary("STOR " + REMOTE, io.BytesIO(prepared))
            if digest(read(ftp)) != info["prepared_sha256"]:
                raise RuntimeError("Deployed CSS hash mismatch")
            print("LIVE DESIGN DEPLOYED AND VERIFIED: " + info["prepared_sha256"])
        except BaseException:
            if attempted:
                ftp.storbinary("STOR " + REMOTE, io.BytesIO(original))
                if read(ftp) != original:
                    raise RuntimeError("URGENT: restoration verification failed")
                print("Previous live CSS restored and verified")
            raise
    finally:
        ftp.close()


if __name__ == "__main__":
    command = sys.argv[1] if len(sys.argv) > 1 else ""
    if command == "prepare":
        prepare()
    elif command == "apply":
        apply()
    else:
        raise SystemExit("Usage: deploy_live_design.py prepare|apply")
