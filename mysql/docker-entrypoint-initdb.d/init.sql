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
    agent_title VARCHAR(255) NOT NULL,
    agent_info VARCHAR(255) NOT NULL,
    agent_point1 VARCHAR(255) NOT NULL,
    agent_point2 VARCHAR(255) NOT NULL,
    agent_point3 VARCHAR(255) NOT NULL,
    start_display TIMESTAMP NOT NULL,
    end_display TIMESTAMP NOT NULL,
    hide INT NOT NULL
);

INSERT INTO
    agents
SET
    agent_name = 'まいなび新卒紹介',
    agent_pic = 'mainabi.jpg',
    agent_tag = '1,2,3',
    agent_tagname = 'ベンチャー、大手、ベンチャー',
    agent_title = '国内トップクラスのデータベースで幅広い求人紹介を見つけられる！',
    agent_info = '大手人材会社のマイナビが運営するこちらの就活エージェント。人材業界最大手の企業だからこそ、企業データを豊富に持っており紹介できる企業が幅広いといえます。東京・名古屋・大阪・京都と4つの拠点があるうえ、マイナビ全体の営業担当が全国各地で活動。地方の就職をサポートする体制が整っていることが魅力です。また、マイナビは展開しているすべてのサービスで面談を徹底していることが特徴。時間をかけて面談がしたい人向きのエージェントです。',
    agent_point1 = '新卒カテゴリから深掘り（地方学生・留学生など）したコンテンツを用意',
    agent_point2 = '全国各地での企業マッチングセミナーを開催',
    agent_point3 = '「採用情報の見方」「OB・OG訪問のマナー」など、全15に渡る就活コラムを掲載',
    start_display = '2022-05-24 00:00:00',
    end_display = '2022-12-24 00:00:00',
    hide = 0;

INSERT INTO
    agents
SET
    agent_name = 'irodasSALON',
    agent_pic = 'irodas.png',
    agent_tag = '1,2,3',
    agent_tagname = 'ベンチャー、大手、ベンチャー',
    agent_title = '設立5年のベンチャー企業が運営！仲間を見つけやすい！',
    agent_info = '「irodas」はもともと、関西の学生を支援するために立ち上げられた就活エージェントです。最近では関東にも進出しており、多くの学生が利用しています。登録学生同士で交流できるイベントが盛んなので、情報交換したり、悩みを相談したりできる仲間を見つけやすいでしょう。2022年2月現在で、23卒の登録者は1万人以上です。サービス開始が2017年とまだ若いサービスであることから、紹介企業は大手に劣る可能性があるものの、学生同士のコミュニティに参加できることはメリット。登録して損はないでしょう。',
    agent_point1 = '学生が集まるコミュニティ型の就活支援サービス',
    agent_point2 = '内定先はキーエンスや野村不動産、サイバーエージェントなど大手・有名企業が多数！',
    agent_point3 = '2週間に1回、社会情勢についてなど就活に役立つ無料の講義',
    start_display = '2022-05-24 00:00:00',
    end_display = '2022-12-24 00:00:00',
    hide = 0;

INSERT INTO
    agents
SET
    agent_name = 'キャリアスタート',
    agent_pic = 'careerstart.png',
    agent_tag = '1,2,3',
    agent_tagname = 'ベンチャー、大手、ベンチャー',
    agent_title = '小規模イベントで社員と距離を縮められる！',
    agent_info = '「キャリアチケット」は人材業界大手のレバレジーズが運営する就活エージェントです。レバレジーズは、規模が大きい会社であるもののベンチャー気質であるため、企業の規模を問わず幅広い質問に答えてくれる可能性が高いといえます。23卒向けのイベントでは、自己分析や履歴書添削などのセミナーだけでなく、2時間で3社の人事担当者とじっくり話すことができる「自分に合う企業が見つかる合同イベント」を開催。内定に直結させられるイベントなので、参加して損はないでしょう。',
    agent_point1 = '有名企業ではなく「働きやすい企業を選ぶ」を価値観としている',
    agent_point2 = '交流スペース「キャリアチケットカフェ」では現役の社会人から生の情報を得られる',
    agent_point3 = 'セミナー（面接対策・就活スケジュール）が盛んでスマホから検索しやすい',
    start_display = '2022-05-24 00:00:00',
    end_display = '2022-12-24 00:00:00',
    hide = 0;

INSERT INTO
    agents
SET
    agent_name = 'JobSpring',
    agent_pic = 'jobspring.png',
    agent_tag = '1,2,3',
    agent_tagname = 'ベンチャー、大手、ベンチャー',
    agent_title = '適性検査を用いて就活生にぴったりの企業を紹介！',
    agent_info = '「JobSpring」は、32個の質問で構成される適性検査とアドバイザーとの面談から最適な企業を見つけ出すと謳う就活エージェントです。面談で聞いた希望条件や人柄から企業を紹介するほかのエージェントと異なり、「JobSpring」はストレス耐性といったデータを基に適正のある企業を紹介してくれます。ただし、適性がある企業が行きたい企業ではないことには注意が必要です。適性検査も自分を理解するひとつの手なので、興味がある人はぜひ登録してください。',
    agent_point1 = '有名企業ではなく「働きやすい企業を選ぶ」を価値観としている',
    agent_point2 = 'SE志向、ベンチャー志向など、ひとりひとりの特性に分けたイベントも開催',
    agent_point3 = 'エージェントによる手厚いサポートNo.1を獲得',
    start_display = '2022-05-24 00:00:00',
    end_display = '2022-12-24 00:00:00',
    hide = 0;

INSERT INTO
    agents
SET
    agent_name = 'Meets Company',
    agent_pic = 'meetscompany.png',
    agent_tag = '1,2,3',
    agent_tagname = 'ベンチャー、大手、ベンチャー',
    agent_title = '適性検査を用いて就活生にぴったりの企業を紹介！',
    agent_info = 'Meets Companyは全国各地、一年中いつでも学生と接点を持つことができ、通年での母集団形成・採用計画が立てられるイベントです。座談会形式で直接マッチングできる就活支援プログラムなので、一般的な合同説明会よりも学生とじっくりと話すことができます。大手ナビ媒体での採用がうまくいっていない場合でも、成長意欲の高い学生とのマッチングを実現します。業種・業界、採用課題などの情報に基づき、貴社のニーズにマッチした学生のみをご紹介することで、効率的に採用活動をおこなうことができます。',
    agent_point1 = '企業の社長・人事と直接交流のできる合同説明会を開催',
    agent_point2 = '合同説明会は全国各地で開かれる',
    agent_point3 = 'コンサルタントとの個別面談による就活サポートあり',
    start_display = '2022-05-24 00:00:00',
    end_display = '2022-12-24 00:00:00',
    hide = 0;

INSERT INTO
    agents
SET
    agent_name = 'シンアド就活',
    agent_pic = 'synad.png',
    agent_tag = '1,2,3',
    agent_tagname = 'ベンチャー、大手、ベンチャー',
    agent_title = '珍しい広告・IT業界専門エージェント！豊富なセミナーが魅力！',
    agent_info = 'Meets イングリウッドが運営する「シンアド就活」は、主に広告・PR・IT業界を専門に就活支援を行うエージェントです。業界が絞られている分、業界動向などの情報を詳しく聞きやすいものの、利用者が限られる点には注意しましょう。シンアド就活は、セミナー開催が豊富であることが魅力。3月から4月までは、ほとんど毎日セミナー・説明会を開催しています。広告・IT業界を志望する人は、登録の価値がある就活エージェントです。',
    agent_point1 = 'クリエイティブ業界に行きたいと決まっている人におすすめ',
    agent_point2 = '予算や再方針に合わせて2種類のプランから選択できる',
    agent_point3 = '担当者は全員業界出身者で構成されている',
    start_display = '2022-05-24 00:00:00',
    end_display = '2022-12-24 00:00:00',
    hide = 0;

INSERT INTO
    agents
SET
    agent_name = 'キャリセン',
    agent_pic = 'careecen.png',
    agent_tag = '1,2,3',
    agent_tagname = 'ベンチャー、大手、ベンチャー',
    agent_title = '専属エージェントと学生一人ひとりの距離が近い！',
    agent_info = '新卒専門の就活エージェント「シンクトワイス株式会社」が運営する総合職を目指す学生向けのサービスです。企業向け採用コンサル歴10年の実績を持ち、これまで累計50,000人の学生の就活支援をしています。紹介実績数は1,000社にのぼり、企業と直接対峙しているコンサルタントによる「ホンネ」ベースの就活相談や企業紹介、就活イベントの主催などを行う会社です。ナビサイトだけでは出会えない、新進気鋭のベンチャー企業や業界トップのシェアを持つ優良企業をご紹介しています。',
    agent_point1 = '先輩利用者のES解答例や体験談もチェックできる',
    agent_point2 = '初回から専任のコンサルタントと1時間じっくり面談',
    agent_point3 = '累計6万人が利用してきた豊富な経験と実績',
    start_display = '2022-05-24 00:00:00',
    end_display = '2022-12-24 00:00:00',
    hide = 0;

INSERT INTO
    agents
SET
    agent_name = '就職エージェントneo',
    agent_pic = 'neo.png',
    agent_tag = '1,2,3',
    agent_tagname = 'ベンチャー、大手、ベンチャー',
    agent_title = '納得できる"企業の内定獲得を目指す！',
    agent_info = '就職エージェントneoは、就活生の皆さんの適性や長所に合わせ、最大の活躍ができる企業探しをお手伝いする、新卒採用に特化した就職支援サービスです。「すべての就活生が運命の1社と出合う」を目指すゴールとし、1人ひとり違う理想の働き方を実現するためにできるだけ多く適性に合った選択肢を提供しています。商社やメーカー、マスコミ、ITをはじめ多様な業界の企業を効率よく紹介しています。',
    agent_point1 = '都市圏（東京・名古屋・大阪・福岡）の主要駅に拠点をもつ',
    agent_point2 = '紹介企業が「何を学生に求めているか」の採用ニーズまでを提示',
    agent_point3 = '人柄重視の個別面談を実施',
    start_display = '2022-05-24 00:00:00',
    end_display = '2022-12-24 00:00:00',
    hide = 0;

INSERT INTO
    agents
SET
    agent_name = 'キャリアチケット',
    agent_pic = 'careerticket.jpg',
    agent_tag = '1,2,3',
    agent_tagname = 'ベンチャー、大手、ベンチャー',
    agent_title = '「量より質」！就活生の価値観に合う企業だけを紹介！',
    agent_info = '自己分析から内定獲得まで、就活アドバイザーがマンツーマンで、状況に合わせて就活をサポートします。単に人気企業や表面的な興味にもとづいた企業選びではなく、入社後も活躍でき、長期的にキャリアを形成していけるような企業への就職を支援します。後悔しないキャリア選びと内定獲得を両立させるためのノウハウを学べる、業界研究、自己分析、ES対策、面接対策、グループディスカッションなど、就活の時期に応じた内容の無料セミナーの実施もあります。',
    agent_point1 = '有名企業ではなく「働きやすい企業を選ぶ」を価値観としている',
    agent_point2 = '交流スペース「キャリアチケットカフェ」では現役の社会人から生の情報を得られる',
    agent_point3 = 'セミナー（面接対策・就活スケジュール）が盛んでスマホから検索しやすい',
    start_display = '2022-05-24 00:00:00',
    end_display = '2022-12-24 00:00:00',
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
    agent_id INT NOT NULL,
    agent VARCHAR(255) NOT NULL,
    deleted_at DATETIME,
    status VARCHAR(255) DEFAULT '有効' 
);

INSERT INTO
    students_agent (student_id, agent_id, agent, deleted_at)
VALUES
    (1, 1, 'agent1', NULL),
    (2, 2, 'agent2', NULL),
    (2, 1, 'agent1', NULL),
    (3, 1, 'agent1', NULL),
    (4, 2, 'agent2', NULL);

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

