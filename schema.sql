CREATE DATABASE YETICAVE
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE user (
	user_id		INT AUTO_INCREMENT PRIMARY KEY,
	name 	VARCHAR(64),
	email		VARCHAR(64),
	password	VARCHAR(64),
	contact_details	TEXT,
	avatar_path	VARCHAR(100),
	dt_add		DATETIME
);

CREATE TABLE category (
	category_id		INT AUTO_INCREMENT PRIMARY KEY,
	ru_name		VARCHAR(64),
	eng_name	VARCHAR(64)
);

CREATE TABLE lot (
	lot_id		INT AUTO_INCREMENT PRIMARY KEY,
	name		VARCHAR(64),
	specification	TEXT,
	start_price	DECIMAL,
	step_price	INT,
	category_id	INT,
	user_id		INT,
	user_win	INT,
	dt_add DATETIME,
	dt_close	DATETIME,
	pic_path	VARCHAR(100),
	FOREIGN KEY (category_id) REFERENCES category(category_id),
	FOREIGN KEY (user_id) REFERENCES user(user_id),
	FOREIGN KEY (user_win) REFERENCES user(user_id)
);

CREATE TABLE bet (
	bet_id		INT AUTO_INCREMENT PRIMARY KEY,
	bet		DECIMAL,
	dt_add		DATETIME,
	lot_id		INT,
	user_id	INT,
	FOREIGN KEY (lot_id) REFERENCES lot(lot_id),
	FOREIGN KEY (user_id) REFERENCES user(user_id)
);
