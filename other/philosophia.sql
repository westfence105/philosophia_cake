-- 認証データ
CREATE TABLE IF NOT EXISTS users(
	username VARCHAR(16) PRIMARY KEY,
	password VARCHAR(255)
);

-- プロフィールデータ
CREATE TABLE IF NOT EXISTS profile_data(
	username VARCHAR(16), index(username),
	order_key INT DEFAULT -1,
	type TEXT,
	data TEXT,
--
	FOREIGN KEY(username) REFERENCES users(username)
	ON UPDATE CASCADE
	ON DELETE CASCADE
--
);