/* ================================================================================= */
/* ユーザーテーブル追加 */
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/* 初期ユーザー（password: p@ssw0rd）追加 */
INSERT INTO users (id, name, password, email)
VALUES (1, 'user01', '$2y$10$chRR/dnRQgyJ4gVlscsIc.aiDsFs1QUT/.AiCfPf.Rru5LixtAfP6', 'user01@example.com');


/* ================================================================================= */
/* 記事テーブル追加 */
CREATE TABLE articles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    text LONGTEXT NOT NULL,
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/* 記事ユーザー外部キー追加 */
ALTER TABLE articles ADD CONSTRAINT fk_article_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;