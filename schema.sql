CREATE DATABASE YETICAVE
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE user (
	id		INT AUTO_INCREMENT PRIMARY KEY,
	email		CHAR(64),
	password	CHAR(64),
	contact_details	TEXT,
	avatar_path	CHAR(64),
	dt_add		DATETIME
);

CREATE TABLE category (
	id		INT AUTO_INCREMENT PRIMARY KEY,
	ru_name		CHAR(64),
	eng_name	CHAR(64)
);

CREATE TABLE lot (
	id		INT AUTO_INCREMENT PRIMARY KEY,
	name		CHAR(64),
	specification	CHAR(64),
	start_price	CHAR(64),
	stepprice	CHAR(64),
	category_id	INT,
	user_id		INT,
	user_win	INT,
	dt_close	DATETIME,
	pic_path	CHAR(64),
	FOREIGN KEY (category_id) REFERENCES category(id),
	FOREIGN KEY (user_id) REFERENCES user(id),
	FOREIGN KEY (user_win) REFERENCES user(id)
);

CREATE TABLE bet (
	id		INT AUTO_INCREMENT PRIMARY KEY,
	bet		CHAR(64),
	dt_add		DATETIME,
	lot_id		INT,
	user_id		INT,
	FOREIGN KEY (lot_id) REFERENCES lot(id),
	FOREIGN KEY (user_id) REFERENCES user(id)
);
