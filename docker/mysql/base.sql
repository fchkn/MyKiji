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

/* ================================================================================= */
/* 記事テーブル追加 */
CREATE TABLE articles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    text LONGTEXT NOT NULL,
    tag_1 VARCHAR(255),
    tag_2 VARCHAR(255),
    tag_3 VARCHAR(255),
    tag_4 VARCHAR(255),
    tag_5 VARCHAR(255),
    tag_6 VARCHAR(255),
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/* 記事ユーザー外部キー追加 */
ALTER TABLE articles ADD CONSTRAINT fk_article_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

/* ================================================================================= */
/* お気に入り記事テーブル追加 */
CREATE TABLE favorites (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    article_id INT UNSIGNED NOT NULL,
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/* 記事ユーザー外部キー追加 */
ALTER TABLE favorites ADD CONSTRAINT fk_favorite_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

/* お気に入り記事外部キー追加 */
ALTER TABLE favorites ADD CONSTRAINT fk_favorite_article FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE CASCADE;

/* ================================================================================= */
/* フォローテーブル追加 */
CREATE TABLE follows (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    follow_user_id INT UNSIGNED NOT NULL,
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/* フォロワーユーザー外部キー追加 */
ALTER TABLE follows ADD CONSTRAINT fk_follower_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

/* フォローユーザー外部キー追加 */
ALTER TABLE follows ADD CONSTRAINT fk_follow_user FOREIGN KEY (follow_user_id) REFERENCES users (id) ON DELETE CASCADE;

/* ================================================================================= */
/* トークンテーブル追加 */
CREATE TABLE tokens (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    token VARCHAR(255),
    limit_time INT UNSIGNED,
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/* フォロワーユーザー外部キー追加 */
ALTER TABLE tokens ADD CONSTRAINT fk_token_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;