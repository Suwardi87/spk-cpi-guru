CREATE DATABASE spk_cpi;
USE spk_cpi;

CREATE TABLE karyawan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100),
  jabatan VARCHAR(100)
);

CREATE TABLE kriteria (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_kriteria VARCHAR(100),
  bobot DECIMAL(5,2)
);

CREATE TABLE penilaian (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_karyawan INT,
  id_kriteria INT,
  nilai INT,
  FOREIGN KEY (id_karyawan) REFERENCES karyawan(id),
  FOREIGN KEY (id_kriteria) REFERENCES kriteria(id)
);

CREATE TABLE admin (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50),
  password VARCHAR(255)
);

INSERT INTO admin (username, password) VALUES 
('admin', MD5('admin123'));
