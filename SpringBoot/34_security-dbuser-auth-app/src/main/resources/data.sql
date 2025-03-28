-- 認証テーブルへのダミーデータの追加
-- password:adminpass
INSERT INTO user_auth (username, password, authority) VALUES
('admin', '$2a$10$NaiOhy/jlwIpJsNBidMldeIHIh0zGncy32/tmGqHh52gYTnRIzpHm', 'ADMIN');

-- password:staffpass
INSERT INTO user_auth (username, password, authority) VALUES
('staff', '$2a$10$QZJ8Y6EBoORFqMo6ponQm.4Lpm03pfrelDLNOacPBttByQoRQ79I.', 'STAFF');

-- password:asoyu
INSERT INTO user_auth (username, password, authority)
VALUES ('yu', '$2a$10$9S7gQY0mWXuKAiAMZYQpx.LURwXHHrBac/b1L1T6C42rEr6KnYIc6', 'STAFF');
