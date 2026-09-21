# Aturan Agen (Agent Rules) - Ejurnal STAI Al Mannan

## Kebijakan Git Commit & Deployment (WAJIB DIIKUTI)

1. **JANGAN Auto-Commit & Auto-Push:**
   - DILARANG melakukan `git commit` dan `git push` secara otomatis setelah mengedit atau mengubah file.
   - Selesai mengedit file, biarkan perubahan tetap berada di direktori kerja lokal (*working tree* / *unstaged changes*).

2. **Hanya Commit dan Push jika Diminta Secara Eksplisit:**
   - Eksekusi `git commit` dan `git push` (yang memicu deployment ke VPS) **HANYA** boleh dilakukan jika pengguna memberikan instruksi eksplisit, misalnya:
     - *"tolong commit"*
     - *"push ke github"*
     - *"sinkronkan ke vps"*
     - *"deploy"*

3. **Uji di Lokal Terlebih Dahulu:**
   - Setiap kali melakukan perubahan fitur atau perbaikan kode, anjurkan pengguna untuk menguji terlebih dahulu di server lokal (`http://localhost:8000`).
   - Tampilkan ringkasan file apa saja yang telah diubah agar pengguna bisa meninjau (*review*) sebelum memutuskan untuk melakukan sinkronisasi ke VPS.
