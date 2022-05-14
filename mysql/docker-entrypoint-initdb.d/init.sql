DROP SCHEMA IF EXISTS shukatsu;

CREATE SCHEMA shukatsu;

USE shukatsu;

-- 管理者情報

DROP TABLE IF EXISTS craft_users;

CREATE TABLE craft_users (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  password_conf VARCHAR(255) NOT NULL
);

INSERT INTO
  craft_users
SET
  email = 'admin@boozer.com',
  password = sha1('password'),
  password_conf = sha1('password');

-- エージェント担当者情報

DROP TABLE IF EXISTS agent_users;

CREATE TABLE agent_users (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  password_conf VARCHAR(255) NOT NULL,
  agent_name VARCHAR(255) NOT NULL
);

INSERT INTO
  agent_users
SET
  email = 'admin@agent.com',
  password = sha1('password'),
  password_conf = sha1('password'),
  agent_name = 'agent1'; 
INSERT INTO
  agent_users
SET
  email = 'admin2@agent.com',
  password = sha1('password'),
  password_conf = sha1('password'),
  agent_name = 'agent2'; 

-- エージェント情報

DROP TABLE IF EXISTS agents;

CREATE TABLE agents (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  agent_name VARCHAR(255) NOT NULL,
  agent_pic VARCHAR(255) NOT NULL,
  agent_tag VARCHAR(255) NOT NULL,
  agent_tagname VARCHAR(255) NOT NULL,
  agent_info VARCHAR(255) NOT NULL,
  agent_display INT NOT NULL
);

INSERT INTO
  agents
SET
  agent_name = 'agent1',
  agent_pic = 'agent1.png',
  agent_tag = '1,2,3',
  agent_tagname = 'ベンチャー、大手、ベンチャー',
  agent_info = 'はい！',
  agent_display = 3;

-- DROP TABLE IF EXISTS agents_master;

-- CREATE TABLE agents_master (
--   id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
--   agent_name VARCHAR(255) NOT NULL,
--   agent_pic VARCHAR(255) NOT NULL,
--   -- agent_tag VARCHAR(255) NOT NULL,
--   agent_info VARCHAR(255) NOT NULL,
--   agent_display INT NOT NULL
-- );

-- INSERT INTO
--   agents_master
-- SET
--   agent_name = 'agent1',
--   agent_pic = 'agent1.png',
--   -- agent_tag = '文系''オンラインあり',
--   agent_info = '強い！強い！強い！強い！強い！強い！強い！強い！強い！強い！',
--   agent_display = 3;


-- DROP TABLE IF EXISTS agents_tags;

-- CREATE TABLE agents_tags (
--   id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
--   agent_id INT NOT NULL,
--   tag_id INT NOT NULL
-- );

-- INSERT INTO
--   agents_tags (agent_id, tag_id)
-- VALUES
-- (1, 2),
-- (1, 3),
-- (1, 4);



-- 学生情報

-- DROP TABLE IF EXISTS students;

-- CREATE TABLE students (
--   id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
--   name VARCHAR(255) NOT NULL,
--   email VARCHAR(255) NOT NULL,
--   phone INT NOT NULL,
--   university VARCHAR(255) NOT NULL,
--   faculty VARCHAR(255) NOT NULL,
--   address VARCHAR(255) NOT NULL,
--   grad_year INT NOT NULL,
--   agent VARCHAR(255) NOT NULL
-- );

-- INSERT INTO students (name, email, phone, university, faculty, address, grad_year, agent) 
-- VALUES
-- ('山田太郎',
-- 'taroyamada@gmail.com',
-- 1111111,
-- '〇〇大学',
-- '〇〇学科',
-- '東京都〇〇区1-1-1',
-- 25,
-- 'agent1'),
-- ('西川航平',
-- 'kohei@gmail.com',
-- 0000001,
-- '〇〇大学',
-- '〇〇学科',
-- '東京都〇〇区1-1-1',
-- 25,
-- 'agent2'),
-- ('寺嶋里紗',
-- 'risa@gmail.com',
-- 0000002,
-- '〇〇大学',
-- '〇〇学科',
-- '東京都〇〇区1-1-1',
-- 25,
-- 'agent2'),
-- ('多田一稀',
-- 'kazuki@gmail.com',
-- 0000003,
-- '〇〇大学',
-- '〇〇学科',
-- '東京都〇〇区1-1-1',
-- 25,
-- 'agent2');


DROP TABLE IF EXISTS students_contact;

CREATE TABLE students_contact (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone INT NOT NULL,
  university VARCHAR(255) NOT NULL,
  faculty VARCHAR(255) NOT NULL,
  address VARCHAR(255) NOT NULL,
  grad_year INT NOT NULL
);

INSERT INTO students_contact (name, email, phone, university, faculty, address, grad_year) 
VALUES
('山田太郎',
'taroyamada@gmail.com',
1111111,
'〇〇大学',
'〇〇学科',
'東京都〇〇区1-1-1',
25),
('西川航平',
'kohei@gmail.com',
0000001,
'〇〇大学',
'〇〇学科',
'東京都〇〇区1-1-1',
25),
('寺嶋里紗',
'risa@gmail.com',
0000002,
'〇〇大学',
'〇〇学科',
'東京都〇〇区1-1-1',
25),
('多田一稀',
'kazuki@gmail.com',
0000003,
'〇〇大学',
'〇〇学科',
'東京都〇〇区1-1-1',
25);

DROP TABLE IF EXISTS students_agent;

CREATE TABLE students_agent (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  student_id INT NOT NULL,
  agent VARCHAR(255) NOT NULL
);

INSERT INTO students_agent (student_id, agent) 
VALUES
(1,
'agent1'),
(2,
'agent2'),
(2,
'agent1'),
(3,
'agent1'),
(4,
'agent2');

-- join するためのコード
-- select students_contact.id, students_contact.name, students_contact.email, students_contact.phone, students_contact.university, students_contact.faculty, students_contact.address, students_contact.grad_year, students_agent.agent from students_contact join students_agent on students_contact.id = students_agent.student_id;




-- タグのカテゴリー

DROP TABLE IF EXISTS tag_categories;

CREATE TABLE tag_categories (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  tag_category VARCHAR(255) NOT NULL,
  tag_category_desc VARCHAR(255) NOT NULL
);

INSERT INTO tag_categories(tag_category, tag_category_desc)
VALUES
  ('運営会社の規模','運営会社の規模の説明'),
  ('登録会社の規模','登録会社の規模の説明'),
  ('紹介企業の数','紹介企業の数の説明');

-- タグ一覧

DROP TABLE IF EXISTS tag_options;

CREATE TABLE tag_options (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  category_id INT NOT NULL,
  tag_option VARCHAR(255) NOT NULL,
  tag_color VARCHAR(255) NOT NULL
);

INSERT INTO tag_options(category_id, tag_option, tag_color)
VALUES
  (1,'ベンチャー', '#CF7C7C'),
  (1,'大手', '#7C85CF'),
  (2,'ベンチャー', '#CF7C7C'),
  (2,'大手', '#7C85CF'),
  (3,'300社〜', '#CF7C7C'),
  (3,'500社〜', '#7C85CF'),
  (3,'1000社〜', '#F3AF56');


DROP TABLE IF EXISTS agent_tag_options;

CREATE TABLE agent_tag_options (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  tag_option_id INT NOT NULL,
  agent_id INT NOT NULL
);

INSERT INTO agent_tag_options(tag_option_id, agent_id)
VALUES
  (1,1),
  (2,1),
  (3,1);









