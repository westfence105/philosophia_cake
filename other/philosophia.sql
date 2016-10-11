-- 認証データ
CREATE TABLE IF NOT EXIST users(
	username VARCHAR(16) PRIMARY KEY,
	passwoed VARCHAR(255)
);

-- プロフィールデータ
CREATE TABLE IF NOT EXIST profile_data(
	username VARCHAR(16),
	order INT DEFAULT -1,
	type TEXT,
	data TEXT,
--
	FOREIGN KEY(username) REFERENCES users(username)
	ON UPDATE CASCADE
	ON DELETE CASCADE
--
);