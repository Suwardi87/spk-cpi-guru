-- Membuat database dan menggunakannya
CREATE DATABASE IF NOT EXISTS spk_cpi_guru;
USE spk_cpi_guru;

-- Tabel Guru
CREATE TABLE guru (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  jabatan VARCHAR(100) NOT NULL
);

INSERT INTO guru (id, nama, jabatan) VALUES
(1, 'Gusmelda Fitri, S.Ag', 'Kepala Sekolah'),
(2, 'Febria Noza, S.Pd I', 'Guru'),
(3, 'Susi Elmi, S.Pd I', 'Guru'),
(4, 'Riska Fhadillah, S.Pd', 'Guru'),
(5, 'Ernawilis Emardi, S.Pd', 'Guru'),
(6, 'Evi Rahayu, S.Pd', 'Guru'),
(7, 'Reni Ekaputri, S.Pd', 'Guru'),
(8, 'Rina Febriana, S.Pd', 'Guru'),
(9, 'Reni Puspita Ningsih, S.Pd', 'Guru'),
(10, 'Nurfatmawati, S.Pd', 'Guru'),
(11, 'Ridani, S.Pd', 'Guru'),
(12, 'Delvita Hendriaty, S.Pd', 'Guru'),
(13, 'Maiwasti, S.Pd', 'Guru'),
(14, 'Santi Gustina, S.E', 'Guru'),
(15, 'Eniza Sefiawati, A.Md', 'Guru'),
(16, 'Drs. Febrizal Zuardi', 'Guru'),
(17, 'Rismayanti, S.Fil.I', 'Guru'),
(18, 'Syerli Fitriani, S.Pd', 'Guru');

-- Tabel Kriteria
CREATE TABLE kriteria (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_kriteria VARCHAR(100) NOT NULL,
  bobot DECIMAL(5,2) NOT NULL,
  tren ENUM('positif', 'negatif', 'netral') DEFAULT 'netral'
);

INSERT INTO kriteria (id, nama_kriteria, bobot, tren) VALUES
(1, 'Absensi', 0.1, 'positif'),
(2, 'Kedisiplinan', 0.25, 'positif'),
(3, 'Supervisi Kelas', 0.2, 'positif'),
(4, 'Dokumen Pembelajaran', 0.15, 'positif'),
(5, 'Observasi Kelas', 0.3, 'positif');



-- Tabel Penilaian
CREATE TABLE penilaian (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_guru INT NOT NULL,
  id_kriteria INT NOT NULL,
  nilai INT NOT NULL,
  FOREIGN KEY (id_guru) REFERENCES guru(id),
  FOREIGN KEY (id_kriteria) REFERENCES kriteria(id)
);

-- Data Penilaian
INSERT INTO penilaian (id_guru, id_kriteria, nilai) VALUES
(1, 1, 100), (1, 2, 90), (1, 3, 95), (1, 4, 90), (1, 5, 95),
(2, 1, 98), (2, 2, 85), (2, 3, 93), (2, 4, 88), (2, 5, 92),
(3, 1, 95), (3, 2, 90), (3, 3, 95), (3, 4, 90), (3, 5, 95),
(4, 1, 95), (4, 2, 85), (4, 3, 95), (4, 4, 90), (4, 5, 95),
(5, 1, 90), (5, 2, 80), (5, 3, 90), (5, 4, 85), (5, 5, 90),
(6, 1, 95), (6, 2, 85), (6, 3, 95), (6, 4, 90), (6, 5, 95),
(7, 1, 97), (7, 2, 85), (7, 3, 95), (7, 4, 90), (7, 5, 95),
(8, 1, 93), (8, 2, 85), (8, 3, 93), (8, 4, 88), (8, 5, 92),
(9, 1, 98), (9, 2, 85), (9, 3, 95), (9, 4, 90), (9, 5, 95),
(10, 1, 94), (10, 2, 85), (10, 3, 95), (10, 4, 90), (10, 5, 95),
(11, 1, 96), (11, 2, 85), (11, 3, 95), (11, 4, 90), (11, 5, 95),
(12, 1, 92), (12, 2, 85), (12, 3, 95), (12, 4, 90), (12, 5, 95),
(13, 1, 93), (13, 2, 80), (13, 3, 95), (13, 4, 90), (13, 5, 95),
(14, 1, 90), (14, 2, 85), (14, 3, 90), (14, 4, 85), (14, 5, 90),
(15, 1, 92), (15, 2, 85), (15, 3, 95), (15, 4, 90), (15, 5, 95),
(16, 1, 90), (16, 2, 85), (16, 3, 95), (16, 4, 90), (16, 5, 95),
(17, 1, 95), (17, 2, 85), (17, 3, 95), (17, 4, 90), (17, 5, 95),
(18, 1, 91), (18, 2, 85), (18, 3, 95), (18, 4, 90), (18, 5, 95);

-- Tabel Pengguna
CREATE TABLE pengguna (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'kepala_sekolah', 'guru') NOT NULL
);

INSERT INTO pengguna (username, password, role) VALUES
('admin', MD5('admin123'), 'admin'),
('kepala_sekolah', MD5('kepala_sekolah123'), 'kepala_sekolah'),
('guru', MD5('guru123'), 'guru');
