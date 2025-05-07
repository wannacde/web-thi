CREATE DATABASE web;
USE web;

CREATE TABLE NguoiDung (
    ma_nguoi_dung INT PRIMARY KEY AUTO_INCREMENT,
    ten_dang_nhap VARCHAR(50) UNIQUE NOT NULL,
    mat_khau VARCHAR(255) NOT NULL,
    ho_ten VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    vai_tro VARCHAR(20) CHECK (vai_tro IN ('quan_tri', 'giao_vien', 'hoc_sinh')) NOT NULL,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE MonHoc (
    ma_mon_hoc INT PRIMARY KEY AUTO_INCREMENT,
    ten_mon_hoc VARCHAR(100) NOT NULL,
    mo_ta TEXT
);

CREATE TABLE Chuong (
    ma_chuong INT PRIMARY KEY AUTO_INCREMENT,
    ma_mon_hoc INT,
    ten_chuong VARCHAR(100) NOT NULL,
    muc_do VARCHAR(20) CHECK (muc_do IN ('de', 'trung_binh', 'kho')) NOT NULL,
    so_thu_tu INT NOT NULL,
    mo_ta TEXT,
    FOREIGN KEY (ma_mon_hoc) REFERENCES MonHoc(ma_mon_hoc)
);

CREATE TABLE CauHoi (
    ma_cau_hoi INT PRIMARY KEY AUTO_INCREMENT,
    ma_chuong INT,
    noi_dung TEXT NOT NULL,
    loai_cau_hoi VARCHAR(20) CHECK (loai_cau_hoi IN ('trac_nghiem', 'dien_khuyet')) NOT NULL,
    nguoi_tao INT,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_chuong) REFERENCES Chuong(ma_chuong),
    FOREIGN KEY (nguoi_tao) REFERENCES NguoiDung(ma_nguoi_dung)
);

CREATE TABLE DapAn (
    ma_dap_an INT PRIMARY KEY AUTO_INCREMENT,
    ma_cau_hoi INT,
    noi_dung TEXT NOT NULL,
    dung_sai BIT NOT NULL,
    FOREIGN KEY (ma_cau_hoi) REFERENCES CauHoi(ma_cau_hoi)
);

CREATE TABLE BaiThi (
    ma_bai_thi INT PRIMARY KEY AUTO_INCREMENT,
    ma_mon_hoc INT,
    ten_bai_thi VARCHAR(100) NOT NULL,
    tong_so_cau INT NOT NULL,
    thoi_gian INT NOT NULL,
    nguoi_tao INT,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_mon_hoc) REFERENCES MonHoc(ma_mon_hoc),
    FOREIGN KEY (nguoi_tao) REFERENCES NguoiDung(ma_nguoi_dung)
);

CREATE TABLE BaiThi_CauHoi (
    ma_bai_thi INT,
    ma_cau_hoi INT,
    PRIMARY KEY (ma_bai_thi, ma_cau_hoi),
    FOREIGN KEY (ma_bai_thi) REFERENCES BaiThi(ma_bai_thi),
    FOREIGN KEY (ma_cau_hoi) REFERENCES CauHoi(ma_cau_hoi)
);

CREATE TABLE KetQuaBaiThi (
    ma_ket_qua INT PRIMARY KEY AUTO_INCREMENT,
    ma_bai_thi INT,
    ma_nguoi_dung INT,
    diem FLOAT NOT NULL,
    ngay_nop DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_bai_thi) REFERENCES BaiThi(ma_bai_thi),
    FOREIGN KEY (ma_nguoi_dung) REFERENCES NguoiDung(ma_nguoi_dung)
);

CREATE TABLE TraLoiNguoiDung (
    ma_ket_qua INT,
    ma_cau_hoi INT,
    dap_an_chon TEXT,
    dung_sai BIT,
    FOREIGN KEY (ma_ket_qua) REFERENCES KetQuaBaiThi(ma_ket_qua),
    FOREIGN KEY (ma_cau_hoi) REFERENCES CauHoi(ma_cau_hoi)
);