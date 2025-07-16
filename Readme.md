🎯 SPK CPI - Sistem Penunjang Keputusan Reward Karyawan
Sistem ini digunakan untuk membantu manajemen dalam menentukan karyawan yang layak mendapat reward berdasarkan kinerja menggunakan metode Composite Performance Index (CPI).

📌 Fitur Utama
✅ Login Multi-Role: Admin, HRD, Supervisor

✅ CRUD Karyawan

✅ CRUD Kriteria Penilaian

✅ Input Penilaian Kinerja

✅ Perhitungan CPI Otomatis

✅ Visualisasi Langkah CPI (Manual/Matematika)

✅ Status Reward Otomatis (≥80 Layak Reward)

✅ Grafik CPI Per Karyawan (Chart.js)

✅ Cetak PDF Laporan Reward

✅ Manajemen User (pengguna.php)

📊 Metode: Composite Performance Index (CPI)
CPI dihitung dengan rumus:

ini
Salin
Edit
CPI = ∑(nilai × bobot) dari setiap kriteria

Contoh:

| Kriteria      | Nilai | Bobot | Kalkulasi (Nilai × Bobot) |
| ------------- | ----- | ----- | ------------------------- |
| Disiplin      | 85    | 0.25  | 21.25                     |
| Kerja Tim     | 90    | 0.20  | 18.00                     |
| ...           | ...   | ...   | ...                       |
| **Total CPI** |       |       | **X.XX**                  |


Jika CPI ≥ 80, maka karyawan dianggap Layak Mendapatkan Reward.

🔐 Hak Akses
| Role           | Hak Akses                                               |
| -------------- | ------------------------------------------------------- |
| **Admin**      | Kelola semua data (karyawan, kriteria, nilai, user)     |
| **HRD**        | Kelola nilai dan lihat hasil                            |
| **Supervisor** | Hanya bisa melihat hasil CPI (tanpa bisa mengubah data) |


🛠️ Teknologi
💻 PHP Native

🗃️ MySQL

🎨 Bootstrap 5

📈 Chart.js

🖨️ Dompdf (untuk cetak PDF)

📁 spk-cpi/
├── config/
│   └── db.php
├── pages/
│   ├── dashboard.php
│   ├── karyawan.php
│   ├── kriteria.php
│   ├── nilai.php
│   ├── hasil.php
│   ├── cetak_laporan.php
│   └── pengguna.php
├── login.php
├── logout.php
└── index.php


Buat database MySQL → import spk_cpi.sql.

Jalankan di browser via http://localhost/spk-cpi/.

| Role           | Username     | Password        |
| -------------- | ------------ | --------------- |
| **Admin**      | `admin`      | `admin123`      |
| **HRD**        | `hrd`        | `hrd123`        |
| **Supervisor** | `supervisor` | `supervisor123` |


