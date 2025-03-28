-- テーブルが存在したら削除する
DROP TABLE IF EXISTS user_auth;

-- テーブルの作成
-- 認証情報を格納するテーブル
CREATE TABLE user_auth (
	-- ユーザー名：主キー
	username VARCHAR(50) PRIMARY KEY,
	-- パスワード
	password VARCHAR(255) NOT NULL,
    -- 権限
    authority ENUM('ADMIN', 'STAFF')
);
