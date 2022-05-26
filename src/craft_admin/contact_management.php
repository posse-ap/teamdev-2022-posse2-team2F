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
    <title>お問い合わせ管理</title>
</head>

<body>
    <div class="util_container">
        <div class="util_sidebar">
            <div class="util_sidebar_button">
                <a class="util_sidebar_link" href="/craft_admin/home.php">エージェント管理</a>
                <i class="fas fa-angle-right"></i>
            </div>
            <div class="util_sidebar_button">
                <a class="util_sidebar_link" href="/craft_admin/add_agent.php">エージェント追加</a>
                <i class="fas fa-angle-right"></i>
            </div>
            <div class="util_sidebar_button">
                <a class="util_sidebar_link" href="/craft_admin/tag.php">タグ編集・追加</a>
                <i class="fas fa-angle-right"></i>
            </div>
            <div class="util_sidebar_button">
                <a class="util_sidebar_link" href="/craft_admin/students_info.php">学生申し込み一覧</a>
                <i class="fas fa-angle-right"></i>
            </div>
            <div class="util_sidebar_button  util_sidebar_button--selected">
                <a class="util_sidebar_link util_sidebar_link--selected" href="/craft_admin/inquiries.php">お問合せ管理</a>
                <i class="fas fa-angle-right"></i>
            </div>
            <div class="util_sidebar_button">
                <a class="util_sidebar_link" href="/craft_admin/invoice.php">合計請求金額確認</a>
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
                        <div class="tab  active">
                            <a class="tab__link__active" href="contact_management.php">学生から</a>
                        </div>
                        <div class="tab">
                            <a class="tab__link" href="inquiries.php">
                                エージェントから
                            </a>
                        </div>
                        <div class="tab">
                            <a class="tab__link" href="inquiries.php">
                                削除依頼
                            </a>
                        </div>
                    </div>
                </div>
            </div>


            <div class="manageinquiries">
                <div class="content-area">
                    <div class="management__content show">
                        <div class="info">
                            <table class="manageinquiries_table" border=1; style=border-collapse:collapse;>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>


<?php require('../_footer.php'); ?>

</html>