DROP DATABASE IF EXISTS notepad_app;

CREATE DATABASE notepad_app;

USE notepad_app;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255)
);

CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    note_id INT NOT NULL,
    phone_number VARCHAR(255),
    adress VARCHAR(255),
    FOREIGN KEY (note_id) REFERENCES users(id)
);
CREATE TABLE content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content_id INT NOT NULL,
    address VARCHAR(255) NOT NULL,
    FOREIGN KEY (content_id) REFERENCES notes(id)
);


ALTER TABLE notes ADD COLUMN user_id INT NOT NULL;
SELECT * FROM notes WHERE id = :id;


