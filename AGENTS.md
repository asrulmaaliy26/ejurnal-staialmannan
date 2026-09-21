# Aturan Agen (Agent Rules) - Ejurnal STAI Al Mannan

## 1. Kebijakan Git Commit & Deployment (WAJIB DIIKUTI)

1. **JANGAN Auto-Commit & Auto-Push:**
   - DILARANG melakukan `git commit` dan `git push` secara otomatis setelah mengedit atau mengubah file apapun (termasuk file tampilan/UI).
   - Selesai mengedit file, biarkan perubahan tetap berada di direktori kerja lokal (*working tree* / *unstaged changes*).

2. **Hanya Commit dan Push jika Diminta Secara Eksplisit:**
   - Eksekusi `git commit` dan `git push` (yang memicu deployment otomatis ke VPS via GitHub Actions) **HANYA** boleh dilakukan jika pengguna memberikan instruksi eksplisit, misalnya:
     - *"tolong commit"*
     - *"push ke github"*
     - *"sinkronkan ke vps"*
     - *"deploy"*

3. **Uji di Lokal Terlebih Dahulu:**
   - Setiap kali melakukan perubahan tampilan (UI), perbaikan fitur, atau kustomisasi template, anjurkan pengguna untuk melihat preview-nya terlebih dahulu di server lokal (`http://localhost:8000`).
   - Tampilkan ringkasan file apa saja yang telah diubah agar pengguna bisa meninjau (*review*) sebelum memutuskan untuk sinkronisasi ke VPS.

---

## 2. Standar Kustomisasi Tampilan Tanpa GUI (Code-First UI Customization)

Pengguna dan agen dapat mengubah tampilan, tata letak, dan gaya antarmuka langsung melalui file kode tanpa harus menggunakan GUI/Admin Panel OJS:

1. **Struktur File Tampilan:**
   - **Template Frontend:** Terletak di `templates/frontend/` (misal `pages/indexJournal.tpl`, `pages/issue.tpl`, `components/header.tpl`, `components/footer.tpl`).
   - **Styling / CSS:** Terletak di plugin tema yang aktif (misal `plugins/themes/default/` atau file CSS kustom tema).
   - **Modifikasi Tampilan:** Dilakukan dengan mengedit file template Smarty (`.tpl`) dan stylesheet (`.css` / `.less`).

2. **Mekanisme Cache & Preview Lokal:**
   - OJS mengkompilasi file `.tpl` ke dalam folder `cache/t_compile/`.
   - Jika perubahan tampilan tidak langsung muncul saat refresh browser di `http://localhost:8000`:
     - Agen/pengguna dapat mengosongkan folder lokal `cache/t_compile/` agar Smarty langsung merender template terbaru.
     - Hard refresh di browser pengguna (`Ctrl + F5`).

3. **Mekanisme Sinkronisasi Otomatis ke VPS:**
   - Setelah pengguna puas dengan tampilan di lokal dan memberi perintah push, alur CI/CD GitHub Actions (`deploy.yml`) akan mengirim file template & CSS ke VPS.
   - Script deployment pada runner VPS wajib secara otomatis membersihkan `cache/t_compile/` di server, sehingga tampilan baru langsung aktif dan dapat dilihat oleh publik tanpa perlu login ke GUI Admin OJS.
