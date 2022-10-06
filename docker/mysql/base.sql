CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20),
    password VARCHAR(255),
    email VARCHAR(255),
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL
);

INSERT INTO users (id, name, password, email, created, modified)
VALUES (1, 'user01', 'password', 'user01@example.com', NOW(), NOW());