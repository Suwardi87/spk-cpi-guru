CREATE DATABASE spk_cpi;
USE spk_cpi;

CREATE TABLE karyawan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100),
  jabatan VARCHAR(100)
);
INSERT INTO karyawan (id, nama, jabatan) VALUES
(1, 'Ahmad Fauzi', 'Staff Produksi'),
(2, 'Rina Marlina', 'Admin Gudang'),
(3, 'Dedi Irawan', 'Quality Control'),
(4, 'Siti Rohani', 'Operator Mesin'),
(5, 'Budi Santoso', 'Teknisi'),
(6, 'Linda Wahyuni', 'Staff HRD'),
(7, 'Agus Prasetyo', 'Staff Keuangan'),
(8, 'Nur Aini', 'Marketing'),
(9, 'Hendra Gunawan', 'Security'),
(10, 'Dewi Sartika', 'Cleaning Service'),
(11, 'Fajar Rahman', 'Supervisor Produksi'),
(12, 'Yulia Fitri', 'Staff Admin'),
(13, 'Rizki Ananda', 'Operator Forklift'),
(14, 'Tini Susanti', 'Customer Service'),
(15, 'Eko Saputra', 'Maintenance'),
(16, 'Melati Rahma', 'Staff Pembelian'),
(17, 'Dian Oktaviani', 'Admin Penjualan'),
(18, 'Slamet Riyadi', 'Driver'),
(19, 'Fitri Yuliana', 'Staff Packing'),
(20, 'Wawan Setiawan', 'Staff IT'),
(21, 'Andi Kurniawan', 'Analis Produksi'),
(22, 'Rosa Amelia', 'Staff Logistik'),
(23, 'Joko Suyono', 'Mandor Produksi'),
(24, 'Selvi Handayani', 'Staff Pajak'),
(25, 'Farhan Maulana', 'Desain Produk'),
(26, 'Nina Andriani', 'Staff Pemasaran'),
(27, 'Heri Kurnia', 'Petugas Kebersihan'),
(28, 'Desi Anggraini', 'Kasir Kantin'),
(29, 'Arif Rachman', 'Petugas Umum'),
(30, 'Taufik Hidayat', 'Staf Pengadaan'),
(31, 'Yuni Lestari', 'Staff Legal'),
(32, 'Irwan Syahputra', 'Supervisor Gudang'),
(33, 'Maya Kurniasari', 'Admin Produksi'),
(34, 'Bayu Firmansyah', 'Teknisi Elektrik'),
(35, 'Siska Novita', 'Resepsionis');


CREATE TABLE kriteria (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_kriteria VARCHAR(100),
  bobot DECIMAL(5,2)
);
INSERT INTO kriteria (id, nama_kriteria, bobot) VALUES
(1, 'Kedisiplinan', 0.25),
(2, 'Produktivitas Kerja', 0.30),
(3, 'Tanggung Jawab', 0.20),
(4, 'Kerja Sama Tim', 0.15),
(5, 'Inisiatif & Kreativitas', 0.10);


CREATE TABLE penilaian (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_karyawan INT,
  id_kriteria INT,
  nilai INT,
  FOREIGN KEY (id_karyawan) REFERENCES karyawan(id),
  FOREIGN KEY (id_kriteria) REFERENCES kriteria(id)
);

REPLACE INTO penilaian (id_karyawan, id_kriteria, nilai) VALUES
(1, 1, 85), (1, 2, 90), (1, 3, 88), (1, 4, 80), (1, 5, 87),
(2, 1, 78), (2, 2, 85), (2, 3, 82), (2, 4, 75), (2, 5, 80),
(3, 1, 90), (3, 2, 92), (3, 3, 89), (3, 4, 85), (3, 5, 88),
(4, 1, 70), (4, 2, 75), (4, 3, 72), (4, 4, 68), (4, 5, 74),
(5, 1, 88), (5, 2, 90), (5, 3, 86), (5, 4, 84), (5, 5, 85),
(6, 1, 77), (6, 2, 80), (6, 3, 79), (6, 4, 76), (6, 5, 78),
(7, 1, 82), (7, 2, 85), (7, 3, 83), (7, 4, 81), (7, 5, 84),
(8, 1, 91), (8, 2, 94), (8, 3, 90), (8, 4, 88), (8, 5, 92),
(9, 1, 69), (9, 2, 72), (9, 3, 70), (9, 4, 68), (9, 5, 71),
(10, 1, 75), (10, 2, 78), (10, 3, 76), (10, 4, 74), (10, 5, 77),
(11, 1, 84), (11, 2, 86), (11, 3, 85), (11, 4, 83), (11, 5, 87),
(12, 1, 79), (12, 2, 82), (12, 3, 80), (12, 4, 78), (12, 5, 81),
(13, 1, 88), (13, 2, 91), (13, 3, 89), (13, 4, 87), (13, 5, 90),
(14, 1, 73), (14, 2, 76), (14, 3, 74), (14, 4, 72), (14, 5, 75),
(15, 1, 85), (15, 2, 88), (15, 3, 86), (15, 4, 83), (15, 5, 87),
(16, 1, 80), (16, 2, 83), (16, 3, 81), (16, 4, 78), (16, 5, 82),
(17, 1, 77), (17, 2, 79), (17, 3, 78), (17, 4, 75), (17, 5, 76),
(18, 1, 86), (18, 2, 89), (18, 3, 87), (18, 4, 85), (18, 5, 88),
(19, 1, 74), (19, 2, 76), (19, 3, 75), (19, 4, 73), (19, 5, 74),
(20, 1, 92), (20, 2, 95), (20, 3, 93), (20, 4, 90), (20, 5, 94),
(21, 1, 81), (21, 2, 84), (21, 3, 82), (21, 4, 80), (21, 5, 83),
(22, 1, 79), (22, 2, 81), (22, 3, 80), (22, 4, 77), (22, 5, 78),
(23, 1, 83), (23, 2, 86), (23, 3, 84), (23, 4, 82), (23, 5, 85),
(24, 1, 76), (24, 2, 79), (24, 3, 77), (24, 4, 75), (24, 5, 78),
(25, 1, 90), (25, 2, 93), (25, 3, 91), (25, 4, 89), (25, 5, 92),
(26, 1, 82), (26, 2, 84), (26, 3, 83), (26, 4, 81), (26, 5, 82),
(27, 1, 70), (27, 2, 72), (27, 3, 71), (27, 4, 69), (27, 5, 70),
(28, 1, 87), (28, 2, 89), (28, 3, 88), (28, 4, 85), (28, 5, 87),
(29, 1, 74), (29, 2, 77), (29, 3, 75), (29, 4, 73), (29, 5, 76),
(30, 1, 80), (30, 2, 83), (30, 3, 81), (30, 4, 78), (30, 5, 82),
(31, 1, 86), (31, 2, 89), (31, 3, 87), (31, 4, 85), (31, 5, 88),
(32, 1, 78), (32, 2, 80), (32, 3, 79), (32, 4, 77), (32, 5, 78),
(33, 1, 91), (33, 2, 94), (33, 3, 92), (33, 4, 89), (33, 5, 93),
(34, 1, 75), (34, 2, 78), (34, 3, 76), (34, 4, 74), (34, 5, 77),
(35, 1, 83), (35, 2, 86), (35, 3, 84), (35, 4, 82), (35, 5, 85);


CREATE TABLE admin (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50),
  password VARCHAR(255)
);

INSERT INTO admin (username, password) VALUES 
('admin', MD5('admin123'));


CREATE TABLE pengguna (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50),
  password VARCHAR(255),
  role ENUM('admin', 'hrd', 'supervisor')
);

INSERT INTO pengguna (username, password, role) VALUES
('admin', MD5('admin123'), 'admin'),
('hrd', MD5('hrd123'), 'hrd'),
('supervisor', MD5('supervisor123'), 'supervisor');
