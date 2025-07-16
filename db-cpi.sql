CREATE DATABASE spk_cpi_guru;
USE spk_cpi_guru;

CREATE TABLE guru (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100),
  jabatan VARCHAR(100)
);

INSERT INTO guru (id, nama, jabatan) VALUES
(1, 'ZULFAYETIS, S.Pd.MM', 'Kepala Sekolah'),
(2, 'Drs. FEBRIZAL ZUARDI', 'Guru'),
(3, 'ERNA WILIS EMARDI, Pd', 'Guru'),
(4, 'RENI EKA PUTRI, S.Pd', 'Guru'),
(5, 'RIDANI, S.Pd', 'Guru'),
(6, 'RENI PUSPITA NINGSIH, S.Pd', 'Guru'),
(7, 'EVI RAHAYU, S.Pd', 'Guru'),
(8, 'MAIWASTI, S.Pd', 'Guru'),
(9, 'RINA FEBRIANA, S.Pd', 'Guru'),
(10, 'NURFATMAWATI, S.Pd', 'Guru'),
(11, 'SANTI GUSTINA, SE', 'Guru'),
(12, 'ENIZA SEFIYAWATI, A.Md', 'Guru'),
(13, 'DELVITA HENDRIATY, S.Pd', 'Guru'),
(14, 'GUSMELDA FITRI, S.PdI', 'Guru'),
(15, 'SUSI ELMI, S.SosI', 'Guru'),
(16, 'ARIZAL', 'Pelaksana Tata Usaha'),
(17, 'FEBRIA NOZA, S.PdI', 'Guru'),
(18, 'RISKA FADHILLAH, S.Pd', 'Guru'),
(19, 'SHILVIA BANISUSANYA, SE', 'Operator Komputer'),
(20, 'DESI SUSANTI', 'Peg.TU'),
(21, 'IRMISON', 'Penjaga Sekolah'),
(22, 'INDRA WATI, S.Pd', 'Peg.TU'),
(23, 'GUSRINA, S.Pd', 'Peg.TU'),
(24, 'UCI ARDILA PUTRI, SE', 'Peg. Pustaka'),
(25, 'SYERLI FITRIANI, S.Pd', 'Guru');


-- Tabel Kriteria Penilaian
CREATE TABLE kriteria (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_kriteria VARCHAR(100),
  bobot DECIMAL(5,2)
);

-- Bobot CPI (bisa disesuaikan)
INSERT INTO kriteria (id, nama_kriteria, bobot) VALUES
(1, 'Kedisiplinan', 0.25),
(2, 'Kompetensi Pedagogik', 0.30),
(3, 'Tanggung Jawab', 0.20),
(4, 'Kemampuan Komunikasi', 0.15),
(5, 'Inovasi dalam Pembelajaran', 0.10);

-- Tabel Penilaian Guru
CREATE TABLE penilaian (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_guru INT,
  id_kriteria INT,
  nilai INT,
  FOREIGN KEY (id_guru) REFERENCES guru(id),
  FOREIGN KEY (id_kriteria) REFERENCES kriteria(id)
);


-- Penilaian Kinerja Guru & Pegawai SMPN 5 Kubung (25 data)

REPLACE INTO penilaian (id_guru, id_kriteria, nilai) VALUES
(1, 1, 92), (1, 2, 95), (1, 3, 93), (1, 4, 90), (1, 5, 89),
(2, 1, 85), (2, 2, 88), (2, 3, 84), (2, 4, 83), (2, 5, 80),
(3, 1, 80), (3, 2, 85), (3, 3, 83), (3, 4, 82), (3, 5, 78),
(4, 1, 87), (4, 2, 90), (4, 3, 88), (4, 4, 85), (4, 5, 83),
(5, 1, 79), (5, 2, 81), (5, 3, 78), (5, 4, 77), (5, 5, 75),
(6, 1, 84), (6, 2, 87), (6, 3, 85), (6, 4, 82), (6, 5, 80),
(7, 1, 78), (7, 2, 82), (7, 3, 80), (7, 4, 79), (7, 5, 76),
(8, 1, 76), (8, 2, 79), (8, 3, 77), (8, 4, 75), (8, 5, 74),
(9, 1, 81), (9, 2, 84), (9, 3, 83), (9, 4, 80), (9, 5, 78),
(10, 1, 80), (10, 2, 83), (10, 3, 82), (10, 4, 79), (10, 5, 77),
(11, 1, 83), (11, 2, 86), (11, 3, 85), (11, 4, 82), (11, 5, 81),
(12, 1, 75), (12, 2, 78), (12, 3, 76), (12, 4, 74), (12, 5, 73),
(13, 1, 77), (13, 2, 80), (13, 3, 78), (13, 4, 76), (13, 5, 75),
(14, 1, 86), (14, 2, 89), (14, 3, 88), (14, 4, 85), (14, 5, 84),
(15, 1, 82), (15, 2, 85), (15, 3, 83), (15, 4, 81), (15, 5, 80),
(16, 1, 74), (16, 2, 76), (16, 3, 75), (16, 4, 72), (16, 5, 71),
(17, 1, 79), (17, 2, 81), (17, 3, 80), (17, 4, 78), (17, 5, 76),
(18, 1, 75), (18, 2, 78), (18, 3, 76), (18, 4, 73), (18, 5, 72),
(19, 1, 70), (19, 2, 74), (19, 3, 72), (19, 4, 70), (19, 5, 68),
(20, 1, 76), (20, 2, 80), (20, 3, 78), (20, 4, 76), (20, 5, 74),
(21, 1, 65), (21, 2, 68), (21, 3, 66), (21, 4, 64), (21, 5, 62),
(22, 1, 73), (22, 2, 76), (22, 3, 74), (22, 4, 72), (22, 5, 71),
(23, 1, 70), (23, 2, 73), (23, 3, 72), (23, 4, 69), (23, 5, 68),
(24, 1, 78), (24, 2, 82), (24, 3, 80), (24, 4, 77), (24, 5, 76),
(25, 1, 84), (25, 2, 87), (25, 3, 85), (25, 4, 82), (25, 5, 81);


-- Tabel Pengguna (3 role: admin, kepala_sekolah, guru)
CREATE TABLE pengguna (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50),
  password VARCHAR(255),
  role ENUM('admin', 'kepala_sekolah', 'guru')
);

-- Contoh akun login
INSERT INTO pengguna (username, password, role) VALUES
('admin', MD5('admin123'), 'admin'),
('kepala_sekolah', MD5('kepala_sekolah123'), 'kepala_sekolah'),
('guru', MD5('guru123'), 'guru');

