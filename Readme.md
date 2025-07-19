# SISTEM PENDUKUNG KEPUTUSAN PENILAIAN KINERJA GURU UNTUK OPTIMALISASI MANAJEMEN SEKOLAH PADA SMP NEGERI 5 KUBUNG MENGGUNAKAN METODE COMPOSITE PERFORMANCE INDEX (CPI)

📊 **Metode:** Composite Performance Index (CPI)  
CPI dihitung dengan rumus:

> **CPI = ∑(nilai × bobot) dari setiap kriteria**

Contoh:

| Kriteria      | Nilai | Bobot | Kalkulasi (Nilai × Bobot) |
| ------------- | ----- | ----- | ------------------------- |
| Disiplin      | 85    | 0.25  | 21.25                     |
| Kerja Tim     | 90    | 0.20  | 18.00                     |
| ...           | ...   | ...   | ...                       |
| **Total CPI** |       |       | **X.XX**                  |

Jika CPI ≥ 80, maka guru dianggap Layak Mendapatkan Reward.

🔐 **Hak Akses**
| Role           | Hak Akses                                               |
| -------------- | ------------------------------------------------------- |
| **Admin**      | Kelola semua data (guru, kriteria, nilai, user)         |
| **kepala_sekolah** | Hanya bisa melihat hasil CPI (tanpa bisa mengubah data) |
| **guru**       | Kelola nilai dan lihat hasil                            |

🛠️ **Teknologi**
- 💻 PHP Native
- 🗃️ MySQL
- 🎨 Bootstrap 5
- 📈 Chart.js
- 🖨️ Dompdf (untuk cetak PDF)

📁 **Struktur Folder**
```
spk-cpi-guru/
├── config/
│   └── db.php
├── pages/
│   ├── dashboard.php
│   ├── guru.php
│   ├── kriteria.php
│   ├── nilai.php
│   ├── hasil.php
│   ├── cetak_laporan.php
│   └── pengguna.php
├── login.php
├── logout.php
└── index.php
```

Buat database MySQL → import spk_cpi.sql.

Jalankan di browser via http://localhost/spk-cpi-guru/.

| Role           | Username           | Password           |
| -------------- | ------------------ | ------------------ |
| **Admin**      | `admin`            | `admin123`         |
| **kepala_sekolah** | `kepala_sekolah` | `kepala_sekolah123`|
| **guru**       | `guru`             | `guru123`          |

