DROP SCHEMA IF EXISTS shukatsu;

CREATE SCHEMA shukatsu;

USE shukatsu;

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
