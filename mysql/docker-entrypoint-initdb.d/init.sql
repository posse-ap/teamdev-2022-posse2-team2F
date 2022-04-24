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

-- エージェント情報

DROP TABLE IF EXISTS agents;

CREATE TABLE agents (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  agent_name VARCHAR(255) NOT NULL,
  agent_pic VARCHAR(255) NOT NULL,
  agent_tag VARCHAR(255) NOT NULL,
  agent_info VARCHAR(255) NOT NULL,
  agent_display INT NOT NULL
);

INSERT INTO
  agents
SET
  agent_name = 'agent1',
  agent_pic = 'agent1.png',
  agent_tag = '文系''オンラインあり',
  agent_info = '強い！強い！強い！強い！強い！強い！強い！強い！強い！強い！',
  agent_display = 3;

-- 学生情報

DROP TABLE IF EXISTS students;

CREATE TABLE students (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone INT NOT NULL,
  university VARCHAR(255) NOT NULL,
  faculty VARCHAR(255) NOT NULL,
  address VARCHAR(255) NOT NULL,
  grad_year INT NOT NULL,
  agent VARCHAR(255) NOT NULL
);

INSERT INTO
  students (name, email, phone, university, faculty, address, grad_year, agent) 
VALUES
('山田太郎',
'taroyamada@gmail.com',
1111111,
'〇〇大学',
'〇〇学科',
'東京都〇〇区1-1-1',
25,
'agent1'),
('西川航平',
'kohei@gmail.com',
0000000,
'〇〇大学',
'〇〇学科',
'東京都〇〇区1-1-1',
25,
'agent1');