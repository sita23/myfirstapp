create schema sevo collate utf8_general_ci;
ALTER SCHEMA 'sevo'  DEFAULT CHARACTER SET utf8  DEFAULT COLLATE utf8_general_ci ;

create table category
(
	id int auto_increment
		primary key,
	name varchar(20) not null,
	uuid varchar(36) not null
);

create table company
(
	id int auto_increment
		primary key,
	name varchar(50) not null,
	phone_number varchar(11) not null,
	created_at datetime null,
	last_modified_at datetime null,
	uuid varchar(36) not null
);

create table product
(
	id int auto_increment
		primary key,
	name varchar(50) not null,
	stock int not null,
	consumption_date datetime null,
	production_date datetime null,
	created_at datetime not null,
	last_modified_at datetime null,
	price int null,
	category_id int null,
	uuid varchar(36) not null,
	constraint product_category_id_fk
		foreign key (category_id) references category (id)
			on update cascade on delete set null
);

create table company_product
(
	id int auto_increment
		primary key,
	company_id int null,
	product_id int null,
	total int not null,
	uuid varchar(36) not null,
	constraint company_product_company_id_fk
		foreign key (company_id) references company (id),
	constraint company_product_product_id_fk
		foreign key (product_id) references product (id)
);

create table sgk
(
	id int auto_increment
		primary key,
	name varchar(30) not null,
	uuid varchar(36) not null
);

create table patient
(
	id int auto_increment
		primary key,
	tc varchar(11) not null,
	name varchar(50) not null,
	surname varchar(50) not null,
	address varchar(150) null,
	sgk_id int null,
	uuid varchar(36) not null,
	constraint patient_tc_uindex
		unique (tc),
	constraint patient_sgk_id_fk
		foreign key (sgk_id) references sgk (id)
			on update cascade on delete set null
);

create table sales
(
	id int auto_increment
		primary key,
	created_at datetime not null,
	last_modified_at datetime null,
	total int null,
	patient_id int null,
	uuid varchar(36) not null,
	constraint sales_patient_id_fk
		foreign key (patient_id) references patient (id)
			on update cascade on delete set null
);

create table user
(
	id int auto_increment
		primary key,
	user_name varchar(50) not null,
	password varchar(120) not null,
	email varchar(50) not null,
	created_at datetime not null,
	last_modified_at datetime null,
	uuid varchar(36) not null
);

create table token
(
	id int auto_increment
		primary key,
	user_id int null,
	token varchar(40) null,
	expiration_date datetime not null,
	status tinyint null,
	created_at datetime null,
	uuid varchar(36) not null,
	constraint token_token_uindex
		unique (token),
	constraint token_user_id_fk
		foreign key (user_id) references user (id)
			on update cascade on delete set null
);



