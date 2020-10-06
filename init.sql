--既にあるbititemsテーブルにdetail,picture_nameカラムを追加したものを作成する
CREATE TABLE biditems (
  id           INTEGER PRIMARY KEY AUTO_INCREMENT,
  user_id      INTEGER NOT NULL REFERENCES users(id),
  name         VARCHAR(100) NOT NULL,
  detail       VARCHAR(1000) NOT NULL,
  picture_name VARCHAR(100) NOT NULL,
  finished     TINYINT(1) NOT NULL,
  endtime      DATETIME NOT NULL,
  created      DATETIME NOT NULL
) ENGINE = INNODB;
