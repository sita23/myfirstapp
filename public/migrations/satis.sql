create schema sevo collate utf8_general_ci;
ALTER SCHEMA 'sevo'  DEFAULT CHARACTER SET utf8  DEFAULT COLLATE utf8_general_ci ;

create table if not exists Adres
(
	id int auto_increment
		primary key,
	sehir varchar(50) not null,
	il varchar(50) null,
	ilce varchar(50) not null,
	aciklama varchar(50) not null,
	olusturulma_tarihi datetime null,
	son_guncellenme_tarihi datetime null
);

create table if not exists Kategori
(
	ID int auto_increment
		primary key,
	adi varchar(50) not null,
	olusturulma_tarihi datetime not null,
	son_guncellenme_tarihi datetime not null
);

create table if not exists Marka
(
	ID int auto_increment
		primary key,
	adi varchar(50) not null,
	marka_kodu int not null,
	marka_logo varchar(100) null,
	olusturulma_tarihi datetime null,
	son_guncellenme_tarihi datetime null
);

create table if not exists Musteri
(
	ID int auto_increment
		primary key,
	adi varchar(50) not null,
	soyad varchar(50) not null,
	TC varchar(11) not null,
	telefon int not null,
	email varchar(150) null,
	adres_id int null,
	olusturulma_tarihi datetime null,
	son_guncellenme_tarihi datetime null,
	constraint Musteri_TC_uindex
		unique (TC),
	constraint Musteri_Adres_id_fk
		foreign key (adres_id) references Adres (id)
);

create table if not exists Tedarikci
(
	ID int auto_increment
		primary key,
	firma_adi varchar(50) not null,
	yetkili_kisi varchar(50) null,
	yetkili_kisi_unvani varchar(50) not null,
	telefon int not null,
	adres varchar(150) not null,
	olusturulma_tarihi datetime null,
	son_guncellenme_tarihi datetime null
);

create table if not exists Urun
(
	ID int auto_increment
		primary key,
	adi varchar(50) not null,
	urun_kodu int not null,
	fiyat int not null,
	kdv_orani int null,
	indirimli_fiyat int null,
	stok int null,
	kategori_id int null,
	tedarikci_id int null,
	marka_id int null,
	urun_foto varchar(50) null,
	olusturulma_tarihi datetime null,
	son_guncellenme_tarihi datetime null,
	aciklama varchar(150) null,
	constraint Urun_Kategori_ID_fk
		foreign key (kategori_id) references Kategori (ID)
			on update cascade on delete set null,
	constraint Urun_Marka_ID_fk
		foreign key (marka_id) references Marka (ID),
	constraint Urun_Tedarikci_ID_fk
		foreign key (tedarikci_id) references Tedarikci (ID)
			on update cascade on delete set null
);

create table if not exists Siparis_Ozet
(
	ID int auto_increment
		primary key,
	musteri_id int not null,
	urun_id int not null,
	fiyat int null,
	adet int not null,
	olusturulma_tarihi datetime null,
	son_guncellenme_tarihi datetime null,
	constraint Siparis_Ozet_Musteri_ID_fk
		foreign key (musteri_id) references Musteri (ID),
	constraint Siparis_Ozet_Urun_ID_fk
		foreign key (urun_id) references Urun (ID)
);