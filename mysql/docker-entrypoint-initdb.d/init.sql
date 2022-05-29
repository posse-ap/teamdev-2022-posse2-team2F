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
    contract_email VARCHAR(255) NOT NULL,
    notify_email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    password_conf VARCHAR(255) NOT NULL,
    agent_name VARCHAR(255) NOT NULL
);

INSERT INTO
    agent_users
SET
    login_email = 'admin@mainabi.com',
    contract_email = 'contract@mainabi.com',
    notify_email = 'notify@mainabi.com',
    password = sha1('password'),
    password_conf = sha1('password'),
    agent_name = 'まいなび新卒紹介';

INSERT INTO
    agent_users
SET
    login_email = 'admin@irodas.com',
    contract_email = 'contract@irodas.com',
    notify_email = 'notify@irodas.com',
    password = sha1('password'),
    password_conf = sha1('password'),
    agent_name = 'irodasSALON';

INSERT INTO
    agent_users
SET
    login_email = 'admin@careerticket.com',
    contract_email = 'contract@careerticket.com',
    notify_email = 'notify@careerticket.com',
    password = sha1('password'),
    password_conf = sha1('password'),
    agent_name = 'キャリアチケット';

INSERT INTO
    agent_users
SET
    login_email = 'admin@jobspring.com',
    contract_email = 'contract@jobspring.com',
    notify_email = 'notify@jobspring.com',
    password = sha1('password'),
    password_conf = sha1('password'),
    agent_name = 'JobSpring';

INSERT INTO
    agent_users
SET
    login_email = 'admin@meetscompany.com',
    contract_email = 'contract@meetscompany.com',
    notify_email = 'notify@meetscompany.com',
    password = sha1('password'),
    password_conf = sha1('password'),
    agent_name = 'Meets Company';

INSERT INTO
    agent_users
SET
    login_email = 'admin@synad.com',
    contract_email = 'contract@synad.com',
    notify_email = 'notify@synad.com',
    password = sha1('password'),
    password_conf = sha1('password'),
    agent_name = 'シンアド就活';

INSERT INTO
    agent_users
SET
    login_email = 'admin@careecen.com',
    contract_email = 'contract@careecen.com',
    notify_email = 'notify@careecen.com',
    password = sha1('password'),
    password_conf = sha1('password'),
    agent_name = 'キャリセン';

INSERT INTO
    agent_users
SET
    login_email = 'admin@neo.com',
    contract_email = 'contract@neo.com',
    notify_email = 'notify@neo.com',
    password = sha1('password'),
    password_conf = sha1('password'),
    agent_name = '就職エージェントneo';

INSERT INTO
    agent_users
SET
    login_email = 'admin@careerstart.com',
    contract_email = 'contract@careerstart.com',
    notify_email = 'notify@careerstart.com',
    password = sha1('password'),
    password_conf = sha1('password'),
    agent_name = 'キャリアスタート';


DROP TABLE IF EXISTS agent_users_info;

CREATE TABLE agent_users_info (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    dept VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    message VARCHAR(255) NOT NULL,
    agent_name VARCHAR(255) NOT NULL
);

INSERT INTO
    agent_users_info
SET
    user_id = 1,
    name = "英字円戸",
    dept = "〇〇部署",
    image = "ento.png",
    message = "よろしくお願いします。",
    agent_name = "まいなび新卒紹介";

INSERT INTO
    agent_users_info
SET
    user_id = 2,
    name = "栄次苑都",
    dept = "〇〇部署",
    image = "ento2.png",
    message = "就活頑張りましょう！",
    agent_name = "irodasSALON";

-- エージェント情報

DROP TABLE IF EXISTS agents;

CREATE TABLE agents (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    agent_name VARCHAR(255) NOT NULL,
    agent_pic VARCHAR(255) NOT NULL,
    agent_tag VARCHAR(255) NOT NULL,
    agent_tagname VARCHAR(255) NOT NULL,
    agent_title VARCHAR(255) NOT NULL,
    agent_title2 VARCHAR(255) NOT NULL,
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
    agent_tag = '2,4,5,6,7,8,9,11',
    agent_tagname = '大手、大手、特別選考あり、全国、関東、一都三県、関西、総合型',
    agent_title = 'データベースで幅広い求人を見つけられる！',
    agent_title2 = 'データベースで幅広い求人を\n見つけられる！',
    agent_info = '大手人材会社のマイナビが運営するこちらの就活エージェント。人材業界最大手の企業だからこそ、企業データを豊富に持っており紹介できる企業が幅広いといえます。東京・名古屋・大阪・京都と4つの拠点があるうえ、マイナビ全体の営業担当が全国各地で活動。地方の就職をサポートする体制が整っていることが魅力です。また、マイナビは展開しているすべてのサービスで面談を徹底していることが特徴。時間をかけて面談がしたい人向きのエージェントです。',
    agent_point1 = '新卒カテゴリから深掘りしたコンテンツを用意',
    agent_point2 = '全国各地での企業マッチングセミナーを開催',
    agent_point3 = '「採用情報の見方」など、全15に渡る就活コラムを掲載',
    start_display = '2022-05-24 00:00:00',
    end_display = '2022-12-24 00:00:00',
    hide = 0;

INSERT INTO
    agents
SET
    agent_name = 'irodasSALON',
    agent_pic = 'irodas.png',
    agent_tag = '1,3,6,7,8,9,10,11',
    agent_tagname = 'ベンチャー、ベンチャー、全国、関東、一都三県、関西、オンライン面談あり、総合型',
    agent_title = '設立5年のベンチャー企業が運営！',
    agent_title2 = '設立5年のベンチャー企業\nが運営！',
    agent_info = '「irodas」はもともと、関西の学生を支援するために立ち上げられた就活エージェントです。最近では関東にも進出しており、多くの学生が利用しています。登録学生同士で交流できるイベントが盛んなので、情報交換したり、悩みを相談したりできる仲間を見つけやすいでしょう。2022年2月現在で、23卒の登録者は1万人以上です。サービス開始が2017年とまだ若いサービスであることから、紹介企業は大手に劣る可能性があるものの、学生同士のコミュニティに参加できることはメリット。登録して損はないでしょう。',
    agent_point1 = '学生が集まるコミュニティ型の就活支援サービス',
    agent_point2 = '内定先は大手・有名企業が多数！',
    agent_point3 = '2週間に1回、就活に役立つ無料の講義',
    start_display = '2022-05-24 00:00:00',
    end_display = '2022-12-24 00:00:00',
    hide = 0;

INSERT INTO
    agents
SET
    agent_name = 'キャリアチケット',
    agent_pic = 'careerticket.jpg',
    agent_tag = '1,3,6,7,8,9,10,11',
    agent_tagname = 'ベンチャー、ベンチャー、全国、関東、一都三県、関西、オンライン面談あり、総合型',
    agent_title = '小規模イベントで社員と距離を縮められる！',
    agent_title2 = '小規模イベントで社員と距離を\n縮められる！',
    agent_info = '「キャリアチケット」は人材業界大手のレバレジーズが運営する就活エージェントです。レバレジーズは、規模が大きい会社であるもののベンチャー気質であるため、企業の規模を問わず幅広い質問に答えてくれる可能性が高いといえます。23卒向けのイベントでは、自己分析や履歴書添削などのセミナーだけでなく、2時間で3社の人事担当者とじっくり話すことができる「自分に合う企業が見つかる合同イベント」を開催。内定に直結させられるイベントなので、参加して損はないでしょう。',
    agent_point1 = '有名企業ではなく「働きやすい企業を選ぶ」を価値観としている',
    agent_point2 = '交流スペースで現役の社会人から生の情報を得られる',
    agent_point3 = 'セミナーが盛んでスマホから検索しやすい',
    start_display = '2022-05-24 00:00:00',
    end_display = '2022-12-24 00:00:00',
    hide = 0;

INSERT INTO
    agents
SET
    agent_name = 'JobSpring',
    agent_pic = 'jobspring.png',
    agent_tag = '1,3,8,10,11',
    agent_tagname = 'ベンチャー、ベンチャー、一都三県、オンライン面談あり、総合型',
    agent_title = '適性検査を用いて就活生にぴったりの企業を紹介！',
    agent_title2 = '適性検査を用いて就活生に\nぴったりの企業を紹介！',
    agent_info = '「JobSpring」は、32個の質問で構成される適性検査とアドバイザーとの面談から最適な企業を見つけ出すと謳う就活エージェントです。面談で聞いた希望条件や人柄から企業を紹介するほかのエージェントと異なり、「JobSpring」はストレス耐性といったデータを基に適正のある企業を紹介してくれます。ただし、適性がある企業が行きたい企業ではないことには注意が必要です。適性検査も自分を理解するひとつの手なので、興味がある人はぜひ登録してください。',
    agent_point1 = '有名企業ではなく「働きやすい企業を選ぶ」を価値観としている',
    agent_point2 = 'ひとりひとりの特性に分けたイベントも開催',
    agent_point3 = 'エージェントによる手厚いサポートNo.1獲得',
    start_display = '2022-05-24 00:00:00',
    end_display = '2022-12-24 00:00:00',
    hide = 0;

INSERT INTO
    agents
SET
    agent_name = 'Meets Company',
    agent_pic = 'meetscompany.png',
    agent_tag = '1,3,6,7,8,9,10,11',
    agent_tagname = 'ベンチャー、ベンチャー、全国、関東、一都三県、関西、オンライン面談あり、総合型',
    agent_title = 'あなたにとって特別な一社との出会いを！',
    agent_title2 = 'あなたにとって特別な一社との\n出会いを！',
    agent_info = 'Meets Companyは全国各地、一年中いつでも学生と接点を持つことができ、通年での母集団形成・採用計画が立てられるイベントです。座談会形式で直接マッチングできる就活支援プログラムなので、一般的な合同説明会よりも学生とじっくりと話すことができます。大手ナビ媒体での採用がうまくいっていない場合でも、成長意欲の高い学生とのマッチングを実現します。業種・業界、採用課題などの情報に基づき、貴社のニーズにマッチした学生のみをご紹介することで、効率的に採用活動をおこなうことができます。',
    agent_point1 = '企業の人事と直接交流できる合同説明会開催',
    agent_point2 = '合同説明会は全国各地で開かれる',
    agent_point3 = 'コンサルタントとの個別面談によるサポートあり',
    start_display = '2022-05-24 00:00:00',
    end_display = '2022-12-24 00:00:00',
    hide = 0;

INSERT INTO
    agents
SET
    agent_name = 'シンアド就活',
    agent_pic = 'synad.png',
    agent_tag = '1,3,4,5,6,7,8,9,10,12,13',
    agent_tagname = 'ベンチャー、ベンチャー、大手、特別選考あり、全国、関東、一都三県、関西、オンライン面談あり、IT・Web業界、広告業界',
    agent_title = '珍しい広告・IT業界専門エージェント！',
    agent_title2 = '珍しい広告・IT業界専門\nエージェント！',
    agent_info = 'Meets イングリウッドが運営する「シンアド就活」は、主に広告・PR・IT業界を専門に就活支援を行うエージェントです。業界が絞られている分、業界動向などの情報を詳しく聞きやすいものの、利用者が限られる点には注意しましょう。シンアド就活は、セミナー開催が豊富であることが魅力。3月から4月までは、ほとんど毎日セミナー・説明会を開催しています。広告・IT業界を志望する人は、登録の価値がある就活エージェントです。',
    agent_point1 = 'クリエイティブ業界に行きたい人におすすめ',
    agent_point2 = '予算や再方針に合わせて2種類のプランから選択可能',
    agent_point3 = '担当者は全員業界出身者で構成',
    start_display = '2022-05-24 00:00:00',
    end_display = '2022-12-24 00:00:00',
    hide = 0;

INSERT INTO
    agents
SET
    agent_name = 'キャリセン',
    agent_pic = 'careecen.png',
    agent_tag = '2,4,5,8,9,10,11',
    agent_tagname = '大手、大手、特別選考あり、一都三県、関西、オンライン面談あり、総合型',
    agent_title = '専属エージェントと学生一人ひとりの距離が近い！',
    agent_title2 = '専属エージェントと学生\n一人ひとりの距離が近い！',
    agent_info = '新卒専門の就活エージェント「シンクトワイス株式会社」が運営する総合職を目指す学生向けのサービスです。企業向け採用コンサル歴10年の実績を持ち、これまで累計50,000人の学生の就活支援をしています。紹介実績数は1,000社にのぼり、企業と直接対峙しているコンサルタントによる「ホンネ」ベースの就活相談や企業紹介、就活イベントの主催などを行う会社です。ナビサイトだけでは出会えない、新進気鋭のベンチャー企業や業界トップのシェアを持つ優良企業をご紹介しています。',
    agent_point1 = '先輩利用者のES解答例や体験談がチェックできる',
    agent_point2 = '初回から専任のコンサルタントとじっくり面談',
    agent_point3 = '累計6万人が利用してきた豊富な経験と実績',
    start_display = '2022-05-24 00:00:00',
    end_display = '2022-12-24 00:00:00',
    hide = 0;

INSERT INTO
    agents
SET
    agent_name = '就職エージェントneo',
    agent_pic = 'neo.png',
    agent_tag = '1,3,4,6,7,8,9,11',
    agent_tagname = 'ベンチャー、ベンチャー、大手、全国、関東、一都三県、関西、総合型',
    agent_title = '納得できる"企業の内定獲得を目指す！',
    agent_title2 = '納得できる"企業の内定獲得を\n目指す！',
    agent_info = '就職エージェントneoは、就活生の皆さんの適性や長所に合わせ、最大の活躍ができる企業探しをお手伝いする、新卒採用に特化した就職支援サービスです。「すべての就活生が運命の1社と出合う」を目指すゴールとし、1人ひとり違う理想の働き方を実現するためにできるだけ多く適性に合った選択肢を提供しています。商社やメーカー、マスコミ、ITをはじめ多様な業界の企業を効率よく紹介しています。',
    agent_point1 = '都市圏の主要駅に拠点をもつ',
    agent_point2 = '紹介企業が何を学生に求めているかまでを提示',
    agent_point3 = '人柄重視の個別面談を実施',
    start_display = '2022-05-24 00:00:00',
    end_display = '2022-12-24 00:00:00',
    hide = 0;

INSERT INTO
    agents
SET
    agent_name = 'キャリアスタート',
    agent_pic = 'careerstart.png',
    agent_tag = '1,3,5,6,7,8,9,10,11',
    agent_tagname = 'ベンチャー、ベンチャー、特別選考あり、全国、関東、一都三県、関西、オンライン面談あり、総合型',
    agent_title = '量より質！就活生の価値観に合う企業だけ紹介！',
    agent_title2 = '量より質！就活生の価値観に\n合う企業だけ紹介！',
    agent_info = '「キャリアスタート」は、既卒や第二新卒の就職・転職支援に特化したエージェントで、2012年のサービス開始から多くの20代転職者に利用されています。第二新卒やフリーターに特化している分、サポートが手厚いため、利用してみる価値が高いサービスです。企業ごとに採用担当者の特徴や過去の質問事例などを教えてもらえて何度でも納得いくまで練習が可能です。',
    agent_point1 = '未経験からの正社員就職が強み',
    agent_point2 = '企業それぞれに合わせた面接対策',
    agent_point3 = '交流会で情報交換ができる',
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
        'lisa@gmail.com',
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
    (1, 1, 'まいなび新卒紹介', NULL),
    (2, 2, 'irodasSALON', NULL),
    (2, 1, 'まいなび新卒紹介', NULL),
    (3, 1, 'まいなび新卒紹介', NULL),
    (4, 2, 'irodasSALON', NULL);

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
    ('運営会社の規模', 'アドバイザーの生の声が聞けるため、興味のある企業規模と、登録するエージェントの運営会社の規模を合わせるのがおすすめです。', 0),
    ('登録会社の規模', '各エージェントに登録している企業の規模を指すため、興味のある企業規模を選択してください。', 0),
    ('特別選考', 'とにかく早く内定がほしい人は特別選考を選択するのがおすすめです。面接をスキップできたりするケースもございます。', 0),
    ('対応エリア', 'エージェントとの面談は信頼関係がより築きやすい対面でやるのが理想的なので、自宅から通える範囲に拠点があるエージェントを選ぶのがおすすめです。', 0),
    ('オンライン面談', '遠方に住んでいるなどの理由で対面での面談ができない場合はオンライン面談に対応しているエージェントを選ぶのがおすすめです。', 0),
    ('強い分野', 'エージェントによって強い分野があるので、興味のある分野を選択してください。', 0);

DROP TABLE IF EXISTS sort_categories;

CREATE TABLE sort_categories (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    sort_category VARCHAR(255) NOT NULL,
    tag_category_id INT NOT NULL,
    hide INT NOT NULL
);

-- 並び替え用数値
INSERT INTO
    sort_categories(sort_category, tag_category_id, hide)
VALUES
    ('公開求人数', 100, 0),
    ('非公開求人数', 101, 0),
    ('利用者数', 102, 0);

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
    (1, 'ベンチャー', '#0000ff', 0),
    (1, '大手', '#0000ff', 0),

    (2, 'ベンチャー', '#ff0000', 0),
    (2, '大手', '#ff0000', 0),

    -- 5
    (3, '特別選考あり', '#ffa500', 0),

    -- 6~9
    (4, '全国', '#008000', 0),
    (4, '関東', '#008000', 0),
    (4, '一都三県', '#008000', 0),
    (4, '関西', '#008000', 0),

    -- 10
    (5, 'オンライン面談あり', '#800080', 0), 

    -- 11~
    (6, '総合型', '#00ced1', 0),
    (6, 'IT・Web業界', '#00ced1', 0),
    (6, '広告業界', '#00ced1', 0);

DROP TABLE IF EXISTS sort_options;

CREATE TABLE sort_options (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    category_id INT NOT NULL,
    sort_option VARCHAR(255) NOT NULL,
    hide INT NOT NULL
);

INSERT INTO
    sort_options(category_id, sort_option, hide)
VALUES
    (100, '500', 0),
    (100, '200', 0),
    (100, '2000', 0),
    (100, '1000', 0),
    (100, '2000', 0),
    (100, '70', 0),
    (100, '1000', 0),
    (100, '10000', 0),
    (100, '300', 0),

    (101, '-', 0),
    (101, '-', 0),
    (101, '-', 0),
    (101, '1000', 0),
    (101, '-', 0),
    (101, '-', 0),
    (101, '-', 0),
    (101, '2000', 0),
    (101, '-', 0),

    (102, '900000', 0),
    (102, '300000', 0),
    (102, '80000', 0),
    (102, '40000', 0),
    (102, '100000', 0),
    (102, '20000', 0),
    (102, '60000', 0),
    (102, '150000', 0),
    (102, '10000', 0);

DROP TABLE IF EXISTS agent_tag_options;

CREATE TABLE agent_tag_options (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    tag_option_id INT NOT NULL,
    agent_id INT NOT NULL
);

INSERT INTO
    agent_tag_options(tag_option_id, agent_id)
VALUES
    -- 運営会社・登録会社の規模
    (2, 1),
    (4, 1),
    (1, 2),
    (3, 2),
    (1, 3),
    (3, 3),
    (1, 4),
    (3, 4),
    (1, 5),
    (3, 5),
    (1, 6),
    (3, 6),
    (4, 6),
    (2, 7),
    (4, 7),
    (1, 8),
    (3, 8),
    (4, 8),
    (1, 9),
    (3, 9),

    -- 特別選考
    (5, 1),
    (5, 6),
    (5, 7),
    (5, 9);
    

INSERT INTO
    agent_tag_options(tag_option_id, agent_id)
VALUES
-- 対応エリア
    (6, 1),
    (7, 1),
    (8, 1),
    (9, 1),
    (6, 2),
    (7, 2),
    (8, 2),
    (9, 2),
    (6, 3),
    (7, 3),
    (8, 3),
    (9, 3),
    (8, 4),
    (6, 5),
    (7, 5),
    (8, 5),
    (9, 5),
    (6, 6),
    (7, 6),
    (8, 6),
    (9, 6),
    (8, 7),
    (9, 7),
    (6, 8),
    (7, 8),
    (8, 8),
    (9, 8),
    (6, 9),
    (7, 9),
    (8, 9),
    (9, 9),

    -- -- オンライン面談
    (10, 2),
    (10, 3),
    (10, 4),
    (10, 5),
    (10, 6),
    (10, 7),
    (10, 9),

    -- --強い分野
    (11, 1),
    (11, 2),
    (11, 3),
    (11, 4),
    (11, 5),
    (12, 6),
    (13, 6),
    (11, 7),
    (11, 8),
    (11, 9);

DROP TABLE IF EXISTS agent_sort_options;

CREATE TABLE agent_sort_options (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    sort_option_id INT NOT NULL,
    agent_id INT NOT NULL
);

INSERT INTO
    agent_sort_options(sort_option_id, agent_id)
VALUES
    -- 運営会社・登録会社の規模
    (1, 1),
    (10, 1),
    (19, 1),
    (2, 2),
    (11, 2),
    (20, 2),
    (3, 3),
    (12, 3),
    (21, 3),
    (4, 4),
    (13, 4),
    (22, 4),
    (5, 5),
    (14, 5),
    (23, 5),
    (6, 6),
    (15, 6),
    (24, 6),
    (7, 7),
    (16, 7),
    (25, 7),
    (8, 8),
    (17, 8),
    (26, 8),
    (9, 9),
    (18, 9),
    (27, 9);


DROP TABLE IF EXISTS user_contact_form;

CREATE TABLE user_contact_form (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(255) NOT NULL,
    detail VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO 
    user_contact_form (
        title,
        name,
        email,
        phone,
        detail
    )
VALUES
    (
        'その他',
        '山田太郎',
        'taroyamada@gmail.com',
        '11111111111',
        'うわあああああああ'
    ),
    (
        'その他',
        '金子夏蓮',
        'careen@gmail.com',
        '11111111111',
        'うわあああああああ'
    );
--   (1,1),
--   (2,1),
--   (3,1);

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
    agent_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    content VARCHAR(255) NOT NULL,
    details VARCHAR(255) NOT NULL
);

INSERT INTO
    agent_inquiries(agent_name, agent_id, name, email, content, details)
VALUES
    ("まいなび新卒紹介", 1, "太郎", "asd@asdasd", "エージェントの情報変更依頼", "エージェント名が変わりました。");

