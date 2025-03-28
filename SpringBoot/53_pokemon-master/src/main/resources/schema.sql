-- 属性相性テーブル
DROP TABLE IF EXISTS type_effectiveness;
-- 対戦履歴テーブル
DROP TABLE IF EXISTS battles;
-- ユーザーテーブル
DROP TABLE IF EXISTS users;
-- ユーザーポケモンテーブル
DROP TABLE IF EXISTS user_pokemon;
-- ポケモンテーブル
DROP TABLE IF EXISTS pokemon;
-- 技テーブル
DROP TABLE IF EXISTS moves;
-- タイプテーブル
DROP TABLE IF EXISTS types;

-- タイプテーブル
CREATE TABLE types (
    type_id INT AUTO_INCREMENT PRIMARY KEY,
    type_name_kana varchar(255) NOT NULL,
    type_name_kanji varchar(255) NOT NULL

);

-- 技テーブル
CREATE TABLE moves (
    move_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    power INT NOT NULL
);

-- ポケモンテーブル
CREATE TABLE pokemon (
    pokemon_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    hp INT NOT NULL,
    attack INT NOT NULL,
    type_id INT NOT NULL,
    move_id INT NOT NULL,
    FOREIGN KEY (type_id) REFERENCES types(type_id) ON DELETE CASCADE,
    FOREIGN KEY (move_id) REFERENCES moves(move_id) ON DELETE CASCADE
);

-- ユーザーポケモンテーブル
CREATE TABLE user_pokemon (
    user_pokemon_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    pokemon_id INT NOT NULL,
    position TINYINT NOT NULL,
    FOREIGN KEY (pokemon_id) REFERENCES pokemon(pokemon_id) ON DELETE CASCADE,
    UNIQUE (user_id, position),
    CHECK (position BETWEEN 1 AND 3)
);

-- ユーザーテーブル
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    trainer_name VARCHAR(100) NOT NULL,
    trainer_icon VARCHAR(50) NOT NULL,
    trainer_level INT NOT NULL DEFAULT 1,
    partner_pokemon_id INT DEFAULT NULL,
    FOREIGN KEY (partner_pokemon_id) REFERENCES user_pokemon(user_pokemon_id)
);

-- 対戦履歴テーブル
CREATE TABLE battles (
    battle_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    opponent_id INT NOT NULL,
    result ENUM('WIN', 'LOSE') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (opponent_id) REFERENCES users(user_id)
);

-- 属性相性テーブル
CREATE TABLE type_effectiveness (
    type_from INT NOT NULL,
    type_to INT NOT NULL,
    effectiveness DECIMAL(3, 2) NOT NULL,
    FOREIGN KEY (type_from) REFERENCES types(type_id) ON DELETE CASCADE,
    FOREIGN KEY (type_to) REFERENCES types(type_id) ON DELETE CASCADE,
    PRIMARY KEY (type_from, type_to)
);
