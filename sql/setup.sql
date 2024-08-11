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
        user_id INT,
        title VARCHAR(255),
        content TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

CREATE TABLE content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content_id INT NOT NULL,
    address VARCHAR(255) NOT NULL,
    FOREIGN KEY (content_id) REFERENCES notes(id)
);


ALTER TABLE notes ADD COLUMN user_id INT NOT NULL;
SELECT * FROM notes WHERE id = :id;


