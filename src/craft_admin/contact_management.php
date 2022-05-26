<?php
session_start();
include('../_header.php');
require('../dbconnect.php');

// お問合せ：user_contact_formテーブルから取得
$sql = "SELECT * FROM user_contact_form";
$sql_prepare = $db->prepare($sql);
$sql_prepare->execute();
$contact_form_lists = $sql_prepare->fetchAll();
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <title>お問い合わせ管理</title>
</head>

<body>
    <div class="util_container">
        <div class="util_sidebar">
            <div class="util_sidebar_button util_sidebar_button--selected">
                <a class="util_sidebar_link  util_sidebar_link--selected" href="/agent_admin/students_info.php">学生申し込み一覧</a>
                <i class="fas fa-angle-right"></i>
            </div>
            <div class="util_sidebar_button">
                <a class="util_sidebar_link" href="/agent_admin/edit_info.php">担当者情報編集</a>
                <i class="fas fa-angle-right"></i>
            </div>
            <div class="util_sidebar_button">
                <a class="util_sidebar_link" href="">お問合せ</a>
                <i class="fas fa-angle-right"></i>
            </div>
            <div class="util_sidebar_button">
                <a class="util_sidebar_link" href="/agent_admin/invoice.php">請求金額確認</a>
                <i class="fas fa-angle-right"></i>
            </div>
            <div class="util_sidebar_button">
                <a class="util_sidebar_link" href="">ユーザー用サイトへ</a>
                <i class="fas fa-angle-right"></i>
            </div>
        </div>


        <div class="util_content">
            <div class="util_title">
                <h2 class="util_title--text">
                    お問合せ管理
                </h2>
                <div class="tab_container">
                    <div class="tab-area">
                        <div class="tab active">
                            学生から
                        </div>
                        <div class="tab">
                            エージェントから
                        </div>
                        <div class="tab">
                            削除依頼
                        </div>
                    </div>
                </div>
            </div>


            <div class="lists_container">
                <div class="content-area">
                    <div class="management__content show">
                        <div class="info">
                            <table class="contact-form__table">
                                <tr class="invoice__table__big-item">
                                    <th>
                                        id
                                    </th>
                                    <th>
                                        タイトル
                                    </th>
                                    <th>
                                        名前
                                    </th>
                                    <th>
                                        email
                                    </th>
                                    <th>
                                        電話番号
                                    </th>
                                    <th>
                                        詳細
                                    </th>
                                    <th>
                                        お問合せ日時
                                    </th>
                                </tr>
                                <?php
                                foreach ($contact_form_lists as $contact_list) {
                                    echo "<tr>";

                                    echo "<th> ${contact_list['id']} </th>";
                                    echo "<th> ${contact_list['title']} </th>";
                                    echo "<th> ${contact_list['name']} </th>";
                                    echo "<th> ${contact_list['email']} </th>";
                                    echo "<th> ${contact_list['phone']} </th>";
                                    echo "<th> ${contact_list['detail']} </th>";
                                    echo "<th> ${contact_list['created_at']} </th>";
                                    echo "</tr>";
                                };
                                echo "</table>";

                                echo "</div>";
                                ?>
                            </table>
                        </div>

                        <div class="management__content">
                            タブ２の中身です
                        </div>
                        <div class="management__content">
                            タブ3の中身です
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>


<?php require('../_footer.php'); ?>

</html>

<script type="text/javascript">
    $(function() {
        let tabs = $(".tab"); // tabのクラスを全て取得し、変数tabsに配列で定義
        $(".tab").on("click", function() { // tabをクリックしたらイベント発火
            $(".active").removeClass("active"); // activeクラスを消す
            $(this).addClass("active"); // クリックした箇所にactiveクラスを追加
            const index = tabs.index(this); // クリックした箇所がタブの何番目か判定し、定数indexとして定義
            $(".management__content").removeClass("show").eq(index).addClass("show"); // showクラスを消して、contentクラスのindex番目にshowクラスを追加
        })
    })
</script>