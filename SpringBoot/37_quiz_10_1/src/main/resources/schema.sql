--テーブルあったら消す
DROP TABLE IF EXISTS quiz;

--作成
CREATE TABLE `quiz`(
`id` int not null AUTO_INCREMENT,
`question` TEXT not null,
`answer` BOOLEAN not null,
`author` varchar(20) not null,
primary key(`id`)
) ENGINE = InnoDB default CHARSET=utf8mb4 collate=utf8mb4_0900_ai_ci;