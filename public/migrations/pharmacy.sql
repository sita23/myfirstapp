CREATE SCHEMA 'sevo' DEFAULT CHARACTER SET utf8 ;

ALTER SCHEMA 'sevo'  DEFAULT CHARACTER SET utf8  DEFAULT COLLATE utf8_general_ci ;







create table category
(
    id   int auto_increment
        primary key,
    name varchar(20) not null
);



create table company
(
    id               int auto_increment
        primary key,
    name             varchar(50) not null,
    phone_number     varchar(11) not null,
    created_at       datetime    null,
    last_modified_at datetime    null
);
create table patient
(
    id      int auto_increment
        primary key,
    tc      varchar(11)  not null,
    name    varchar(50)  not null,
    surname varchar(50)  not null,
    address varchar(150) null
);

create table product
(
    id               int auto_increment
        primary key,
    name             varchar(50) not null,
    stock            int         not null,
    consumption_date datetime    null,
    production_date  datetime    null,
    created_at       datetime    not null,
    last_modified_at datetime    null,
    price            int         null
);

create table sales
(
    id               int auto_increment
        primary key,
    created_at       datetime not null,
    last_modified_at datetime null,
    total            int      null
);

create table sgk
(
    id   int auto_increment
        primary key,
    name varchar(30) not null
);
alter table product
	add category_id int null;

alter table product
	add constraint product_category_id_fk
		foreign key (category_id) references category (id)
			on update cascade on delete set null;

	alter table patient
	add sgk_id int null;

alter table patient
	add constraint patient_sgk_id_fk
		foreign key (sgk_id) references sgk (id)
			on update cascade on delete set null;


alter table sales
	add patient_id int null;

alter table sales
	add constraint sales_patient_id_fk
		foreign key (patient_id) references patient (id)
			on update cascade on delete set null;









create table user
(
    id int auto_increment,
    user_name varchar(50) not null,
    password varchar(120) not null,
    email varchar(50) not null,
    created_at datetime not null,
    last_modified_at datetime null,
    constraint user_pk
    primary key (id)
)
INSERT INTO `sevo`.`user` (`user_name`, `password`, `email`, `created_at`, `last_modified_at`) VALUES ('sita', '1234', 'sita@gmail.com', '2019-07-04 07:46:57', null)





