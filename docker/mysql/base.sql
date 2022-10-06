CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    created DATETIME DEFAULT NOT NULL,
    modified DATETIME DEFAULT NOT NULL
);

/* password: p@ssw0rd */
INSERT INTO users (id, name, password, email, created, modified)
VALUES (1, 'user01', '$2y$10$chRR/dnRQgyJ4gVlscsIc.aiDsFs1QUT/.AiCfPf.Rru5LixtAfP6', 'user01@example.com', NOW(), NOW());