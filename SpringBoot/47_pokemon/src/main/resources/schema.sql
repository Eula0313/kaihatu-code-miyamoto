-- テーブルが存在したら削除する
DROP TABLE IF EXISTS `trainer`;

-- トレーナーテーブルの作成
CREATE TABLE `trainer` (
    `trainer_id` INT PRIMARY KEY AUTO_INCREMENT,
    `trainer_name` VARCHAR(100) NOT NULL,
    `trainer_level` INT DEFAULT 1,
    `trainer_icon_id` INT NOT NULL,
    `partner_id` INT NOT NULL
) ENGINE=InnoDB;
