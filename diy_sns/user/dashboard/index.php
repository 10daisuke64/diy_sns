<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'] . "/diy_sns";
include $path . "/common/functions.php";

// セッションの有無をチェック
check_session_id("login");

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];
$pdo = connect_to_db();

// -----------------------------
//  投稿内容の取得
// -----------------------------
$sql = "SELECT posts_table.*, GROUP_CONCAT(categories_table.name SEPARATOR ',') AS category, users_table.name, users_table.image_profile FROM posts_table LEFT OUTER JOIN users_table ON posts_table.user_id = users_table.id LEFT OUTER JOIN post_category ON posts_table.id = post_category.post_id LEFT OUTER JOIN categories_table ON categories_table.id = post_category.category_id WHERE posts_table.user_id=:user_id AND posts_table.is_deleted=0 GROUP BY posts_table.id ORDER BY posts_table.created_at DESC";

// $sql = "SELECT posts_table.*, GROUP_CONCAT(categories_table.name SEPARATOR ',') AS category FROM posts_table LEFT OUTER JOIN post_category ON posts_table.id = post_category.post_id LEFT OUTER JOIN categories_table ON categories_table.id = post_category.category_id WHERE posts_table.user_id=:user_id AND posts_table.is_deleted=0 GROUP BY posts_table.id ORDER BY posts_table.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}
// -----------------------------
//  投稿の出力
// -----------------------------
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$output_post = "";
if (!$result) {
  $output_post .= "<p>投稿はありませんでした</p>";
} else {
  $output_post .= output_post_list($result);
}
?>

<!-- header -->
<?php include $path . "/common/header.php"; ?>
<!-- //header -->

<main>
  <section class="section">
    <div class="wrapper">
      <h1>マイページ</h1>
      <p><a href="/diy_sns/user/dashboard/profile.php">プロフィール設定</a></p>
      <p><a href="/diy_sns/user/post/">投稿する</a></p>
      <p><a href="/diy_sns/home/">みんなの投稿を見る</a></p>
      <p><a href="/diy_sns/login/logout.php">ログアウト</a></p>

      <section class="section">
        <h2>自分の投稿一覧</h2>
        <?= $output_post; ?>
      </section>
    </div>
  </section>
</main>

<!-- footer -->
<?php include $path . "/common/footer.php"; ?>
<!-- //footer -->
