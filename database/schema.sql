CREATE DATABASE IF NOT EXISTS studytrack;

USE studytrack;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(200) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE subjects {
    id INT AUTO_INCREMENT PRIMARY_KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
};

CREATE TABLE tasks(
    id INT AUTO_INCREMENT PRIMARY_KEY,
    user_id INT NOT NULL,
    subject_id INT NOT NULL,
    title VARCHAR(175) NOT NULL,
    description TEXT,
    due_date Date,
    status ENUM('pending', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,

    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);