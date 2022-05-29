<?php
session_start();

// ログインしていないままアクセスしようとしている場合エラーページに飛ばす
if (!isset($_SESSION['id'])) {
    header('Location: ./login/login_error.php');
}


include('../_header.php');
require('../dbconnect.php');


$sql = "SELECT * FROM delete_student_application;";
$sql_prepare = $db->prepare($sql);
$sql_prepare->execute();
$delete_application = $sql_prepare->fetchAll();
?>

<body>
    <div class="util_logout">
        <p class="util_logout_email"><?= $_SESSION['email'] ?></p>
        <a href="./login/logout.php">
            ログアウト
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
    <div class="util_container">
        <div class="util_sidebar">
            <div class="util_sidebar_button">
                <a class="util_sidebar_link" href="/craft_admin/home.php">エージェント管理</a>
            </div>
            <div class="util_sidebar_button">
                <a class="util_sidebar_link" href="/craft_admin/add_agent.php">エージェント追加</a>
            </div>
            <div class="util_sidebar_button">
                <a class="util_sidebar_link" href="/craft_admin/tag.php">タグ編集・追加</a>
            </div>
            <div class="util_sidebar_button util_sidebar_button--selected">
                <a class="util_sidebar_link util_sidebar_link--selected" href="/craft_admin/students_info.php">学生申し込み一覧</a>
            </div>
            <div class="util_sidebar_button">
                <a class="util_sidebar_link" href="">ユーザー用サイトへ</a>
            </div>
        </div>

        <div class="util_content">
            <div class="util_title">
                <h2 class="util_title--text">
                    削除申請一覧
                </h2>
            </div>
            <div class="info">
                <!-- 並び替え結果 -->
                <div class="table_container">
                    <table border=1; style=border-collapse:collapse;>
                        <tr>
                            <th>
                                学生番号
                            </th>

                            <th>
                                名前
                            </th>

                            <th>
                                エージェント名
                            </th>
                        </tr>

                        <?php
                        foreach ($delete_application as $student_info) {
                            echo "<tr>";

                            echo "<th>";
                            echo $student_info['student_id'];
                            echo "</th>";

                            echo "<th>";
                            echo $student_info['name'];
                            echo "</th>";

                            echo "<th>";
                            echo $student_info['agent_id'];
                            echo "</th>";

                            echo "</tr>";
                        };
                        echo "</table>";

                        echo "</div>";

                        /*
                        以下、コピペでわからなかったところ
                        結果セットを解放？
                        $all_students_info->free();

                        データベース切断？
                        $mysqli->close();
                        $i = 0;
                        foreach ($rows as $row) {
                        
                        データの破棄
                        if (isset($_SESSION['sort'])) {
                            session_destroy();
                            unset($_SESSION['sort']);
                        }
                        */
                        ?>
                </div>
                </form>
            </div>
        </div>
    </div>


    <?php require('../_footer.php'); ?>
</body>