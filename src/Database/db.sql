create database tongue_spice;
use tongue_spice;
create table user(
    id int not null auto_increment PRIMARY key,
	fname varchar(80) not null,
    lname varchar(80) not null,
    email varchar(120) not null UNIQUE,
    password varchar(255) not null,
    role_id int default(1), -- 0 - admin | 1 - customer
    created_ts timestamp,
    updated_ts datetime
);
create table auth(
    id int not null auto_increment primary key,
    user_id int not null,
    api_key varchar(255)
);
create table address(
	id int auto_increment primary key,
	user_id int not null, -- incase user wants to use a different name?
    firstname varchar(80),
    lastname varchar(80),
    email varchar(180),
	address_type char(4), -- bill | ship
	address_line text not null,
	address_line2 text,
	district varchar(30),
    created_ts timestamp
);

create table payment(
	id int auto_increment primary key,
    amount_paid decimal(10,2) not null,
    payment_type char(2) not null, -- cc db pp,
    description text,
    payment_ts timestamp
);
create table cart(
	id int auto_increment primary key,
    user_id int not null,
    state tinyint, -- 1 still active, 0 cart no longer used
    start_ts timestamp,
    end_ts datetime,
    promo_code_id int null
);
create table sauce(
	id int auto_increment primary key,
    brand varchar(40),
    title varchar(100),
    price decimal(10,2),
    description text,
    img_path text,
    ingredients text,
    added_ts timestamp
);
create table cart_item(
id int auto_increment primary key,
sauce_id int not null, 
qty int default(1),
item_total decimal(10,2),
added_ts timestamp,
cart_id int not null
);
create table reciept(
	id int primary key auto_increment,
    user_id int not null,
    cart_id int not null,
    tax decimal(10, 2) not null,
    tax_amount decimal(10,2) not null,
    subtotal decimal(10,2) not null,
    total decimal(10,2)  not null,
    item_qty int not null,
    is_paid tinyint default(0) not null, -- 0 - false | 1 true
    payment_id int NULL, -- nullable
    promo_code varchar(12) NULL,
    promo_code_value DECIMAL(10,2) NULL,
    reciept_ts timestamp,
    bill_address_id int not null,
    ship_address_id int not null
);

create table promocodes(
	id int auto_increment primary key,
    code varchar(12) not null,
    value decimal(10,2) not null default(5.00),
    is_used tinyint not null,
    used_ts datetime not null,
    created_ts timestamp
);
/**
- User, - UserCart, - Sauce, - CartItems, - Reciept, - Address, - payment,  - promocodes
**/