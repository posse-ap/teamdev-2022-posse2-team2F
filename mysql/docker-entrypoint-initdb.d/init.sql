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
    login_email VARCHAR(255) UNIQUE NOT NULL,
    contract_email VARCHAR(255) UNIQUE NOT NULL,
    notify_email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    password_conf VARCHAR(255) NOT NULL,
    agent_name VARCHAR(255) NOT NULL
);

INSERT INTO
    agent_users
SET
    login_email = 'admin@agent.com',
    contract_email = 'contract1@agent.com',
    notify_email = 'notify1@agent.com',
    password = sha1('password'),
    password_conf = sha1('password'),
    agent_name = 'agent1';

INSERT INTO
    agent_users
SET
    login_email = 'admin2@agent.com',
    contract_email = 'contract2@agent.com',
    notify_email = 'notify2@agent.com',
    password = sha1('password'),
    password_conf = sha1('password'),
    agent_name = 'agent2';

DROP TABLE IF EXISTS agent_users_info;

CREATE TABLE agent_users_info (
    user_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    dept VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    message VARCHAR(255) NOT NULL
);

INSERT INTO
    agent_users_info
SET
    name = "英時えんと",
    dept = "〇〇部署",
    image = "ento.png",
    message = "よろしくお願いしません！！！！！";

INSERT INTO
    agent_users_info
SET
    name = "栄次えんと",
    dept = "〇〇部署",
    image = "ento2.png",
    message = "就活頑張らなくていいよ！！！！！";

-- エージェント情報

DROP TABLE IF EXISTS agents;

CREATE TABLE agents (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    agent_name VARCHAR(255) NOT NULL,
    agent_pic VARCHAR(255) NOT NULL,
    agent_tag VARCHAR(255) NOT NULL,
    agent_tagname VARCHAR(255) NOT NULL,
    agent_info VARCHAR(255) NOT NULL,
    agent_display INT NOT NULL,
    hide INT NOT NULL
);

INSERT INTO
    agents
SET
    agent_name = 'agent1',
    agent_pic = 'agent1.png',
    agent_tag = '1,2,3',
    agent_tagname = 'ベンチャー、大手、ベンチャー',
    agent_info = 'はい！',
    agent_display = 3,
    hide = 0;


DROP TABLE IF EXISTS students_contact;

CREATE TABLE students_contact (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    -- phone は int だと足りない、bigint だと最初の0が消えちゃう
    phone VARCHAR(255) NOT NULL,
    university VARCHAR(255) NOT NULL,
    faculty VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    grad_year INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO
    students_contact (
        name,
        email,
        phone,
        university,
        faculty,
        address,
        grad_year
    )
VALUES
    (
        '山田太郎',
        'taroyamada@gmail.com',
        1111111,
        '〇〇大学',
        '〇〇学科',
        '東京都〇〇区1-1-1',
        25
    ),
    (
        '西川航平',
        'kohei@gmail.com',
        0000001,
        '〇〇大学',
        '〇〇学科',
        '東京都〇〇区1-1-1',
        25
    ),
    (
        '寺嶋里紗',
        'risa@gmail.com',
        0000002,
        '〇〇大学',
        '〇〇学科',
        '東京都〇〇区1-1-1',
        25
    ),
    (
        '多田一稀',
        'kazuki@gmail.com',
        0000003,
        '〇〇大学',
        '〇〇学科',
        '東京都〇〇区1-1-1',
        25
    );

DROP TABLE IF EXISTS students_agent;

CREATE TABLE students_agent (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    student_id INT NOT NULL,
    agent VARCHAR(255) NOT NULL,
    deleted_at DATETIME,
    status VARCHAR(255) DEFAULT '有効' 
);

INSERT INTO
    students_agent (student_id, agent, deleted_at)
VALUES
    (1, 'agent1', NULL),
    (2, 'agent2', NULL),
    (2, 'agent1', NULL),
    (3, 'agent1', NULL),
    (4, 'agent2', NULL);

-- join するためのコード

-- select students_contact.id, students_contact.name, students_contact.email, students_contact.phone, students_contact.university, students_contact.faculty, students_contact.address, students_contact.grad_year, students_agent.agent, students_agent.deleted_at from students_contact join students_agent on students_contact.id = students_agent.student_id;

-- タグのカテゴリー

DROP TABLE IF EXISTS tag_categories;

CREATE TABLE tag_categories (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    tag_category VARCHAR(255) NOT NULL,
    tag_category_desc VARCHAR(255) NOT NULL,
    hide INT NOT NULL
);

INSERT INTO
    tag_categories(tag_category, tag_category_desc, hide)
VALUES
    ('運営会社の規模', '運営会社の規模の説明', 0),
    ('登録会社の規模', '登録会社の規模の説明', 0),
    ('紹介企業の数', '紹介企業の数の説明', 0);

-- タグ一覧

DROP TABLE IF EXISTS tag_options;

CREATE TABLE tag_options (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    category_id INT NOT NULL,
    tag_option VARCHAR(255) NOT NULL,
    tag_color VARCHAR(255) NOT NULL,
    hide INT NOT NULL
);

INSERT INTO
    tag_options(category_id, tag_option, tag_color, hide)
VALUES
    (1, 'ベンチャー', '#CF7C7C', 0),
    (1, '大手', '#7C85CF', 0),
    (2, 'ベンチャー', '#CF7C7C', 0),
    (2, '大手', '#7C85CF', 0),
    (3, '300社〜', '#CF7C7C', 0),
    (3, '500社〜', '#7C85CF', 0),
    (3, '1000社〜', '#F3AF56', 0);

DROP TABLE IF EXISTS agent_tag_options;

CREATE TABLE agent_tag_options (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    tag_option_id INT NOT NULL,
    agent_id INT NOT NULL
);

INSERT INTO
    agent_tag_options(tag_option_id, agent_id)
VALUES
  (1,1),
  (2,1),
  (3,1);

DROP TABLE IF EXISTS delete_student_application;

CREATE TABLE delete_student_application (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  application_id INT NOT NULL UNIQUE,
  agent_name VARCHAR(255) NOT NULL, 
  response VARCHAR(255) DEFAULT '未対応', 
  time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
--   display INT NOT NULL DEFAULT 1
);

INSERT INTO delete_student_application(application_id, agent_name)
VALUES
    (1,'agent1'),
    (2,'agent1');



-- パスワードリセット関連

DROP TABLE IF EXISTS craft_password_reset;

CREATE TABLE craft_password_reset (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    pass_token VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO
    craft_password_reset(email, pass_token)
VALUES
    ("test@test.com", "test");

DROP TABLE IF EXISTS agent_password_reset;

CREATE TABLE agent_password_reset (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    pass_token VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO
    agent_password_reset(email, pass_token)
VALUES
    ("test2@test.com", "test");



-- お問合せ

DROP TABLE IF EXISTS agent_inquiries;

CREATE TABLE agent_inquiries (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    agent_name VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    content VARCHAR(255) NOT NULL,
    details VARCHAR(255) NOT NULL
);

INSERT INTO
    agent_inquiries(agent_name, name, email, content, details)
VALUES
    ("test", "test", "asd@asdasd", "エージェントの情報変更依頼", "エージェント名が変わりました。");

