CREATE DATABASE YETICAVE
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE user (
	user_id		INT AUTO_INCREMENT PRIMARY KEY,
	name 	VARCHAR(64) NOT NULL,
	email		VARCHAR(128) NOT NULL,
	password	VARCHAR(64) NOT NULL,
	contact_details	TEXT NOT NULL,
	avatar_path	VARCHAR(128) NULL DEFAULT NULL,
	dt_add		DATETIME NOT NULL
);

CREATE TABLE category (
	category_id		INT AUTO_INCREMENT PRIMARY KEY,
	ru_name		VARCHAR(64),
	eng_name	VARCHAR(64)
);

CREATE TABLE lot (
	lot_id		INT AUTO_INCREMENT PRIMARY KEY,
	name		VARCHAR(64) NOT NULL,
	specification	TEXT NOT NULL,
	start_price	DECIMAL NOT NULL,
	step_price	INT NOT NULL,
	category_id	INT NOT NULL,
	user_id		INT NOT NULL,
	user_win	INT NULL DEFAULT NULL,
	dt_add DATETIME NOT NULL,
	dt_close	DATETIME NOT NULL,
	pic_path	VARCHAR(128) NOT NULL,
	FOREIGN KEY (category_id) REFERENCES category(category_id),
	FOREIGN KEY (user_id) REFERENCES user(user_id),
	FOREIGN KEY (user_win) REFERENCES user(user_id)

);

CREATE TABLE bet (
	bet_id		INT AUTO_INCREMENT PRIMARY KEY,
	bet		DECIMAL NOT NULL,
	dt_add		DATETIME NOT NULL,
	lot_id		INT NOT NULL,
	user_id	INT NOT NULL,
	FOREIGN KEY (lot_id) REFERENCES lot(lot_id),
	FOREIGN KEY (user_id) REFERENCES user(user_id)
);
CREATE UNIQUE INDEX email ON user(email);
CREATE INDEX lot_id ON bet(lot_id);
