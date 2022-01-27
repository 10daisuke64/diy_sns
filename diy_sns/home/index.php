<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'] . "/diy_sns";
include($path . "/common/functions.php");

check_session_id("login");

$pdo = connect_to_db();

// カテゴリーidをGETで受け取り
$cat_param = $_GET["category"];

// -----------------------------
//  カテゴリー検索
// -----------------------------
$sql = 'SELECT * FROM categories_table';
$stmt = $pdo->prepare($sql);
try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$output_sort = "";
$output_sort .= "<ul class='c-sortbycat'>";
$output_sort .= "
  <li><a href='/diy_sns/home/'>すべて</a></li>
";
foreach ($result as $record) {
  $output_sort .= "
    <li><a href='/diy_sns/home/?category={$record["id"]}'>{$record["name"]}</a></li>
  ";
}
$output_sort .= "</ul>";

// 表示中のカテゴリーのタイトル
$output_cat_title = "";


// SELECT
//   posts_table.*,
//   GROUP_CONCAT(categories_table.name SEPARATOR ',') AS category,
//   users_table.name,
//   users_table.image_profile
// FROM
// posts_table
// LEFT OUTER JOIN users_table ON posts_table.user_id = users_table.id
// LEFT OUTER JOIN post_category ON posts_table.id = post_category.post_id
// LEFT OUTER JOIN categories_table ON categories_table.id = post_category.category_id
// WHERE posts_table.is_deleted=0
// GROUP BY posts_table.id
// ORDER BY posts_table.created_at DESC



// -----------------------------
//  投稿リストの取得
// -----------------------------
if (!isset($cat_param)) {
  $sql = "SELECT posts_table.*, GROUP_CONCAT(categories_table.name SEPARATOR ',') AS category, users_table.name, users_table.image_profile FROM posts_table LEFT OUTER JOIN users_table ON posts_table.user_id = users_table.id LEFT OUTER JOIN post_category ON posts_table.id = post_category.post_id LEFT OUTER JOIN categories_table ON categories_table.id = post_category.category_id WHERE posts_table.is_deleted=0 GROUP BY posts_table.id ORDER BY posts_table.created_at DESC";
  $stmt = $pdo->prepare($sql);
} else {
  $sql = "SELECT result_table.* FROM post_category LEFT OUTER JOIN ( SELECT posts_table.*, GROUP_CONCAT(categories_table.name SEPARATOR ',') AS category FROM posts_table LEFT OUTER JOIN post_category ON posts_table.id = post_category.post_id LEFT OUTER JOIN categories_table ON categories_table.id = post_category.category_id GROUP BY posts_table.id ) AS result_table ON result_table.id = post_category.post_id WHERE post_category.category_id=:cat_param AND result_table.is_deleted = 0 ORDER BY result_table.created_at DESC";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':cat_param', $cat_param, PDO::PARAM_INT);
}

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($result);
// exit();

// -----------------------------
//  ページネーション設定
// -----------------------------
define('MAX', '5'); // 表示件数
$result_number = count($result); // トータルデータ件数
$max_page = ceil($result_number / MAX); // 小数点切り捨て

// ページ数をGET
if (!isset($_GET['page'])) {
  $now = 1;
} else {
  $now = $_GET['page'];
}

// 表示させるデータのみカット
$start_no = ($now - 1) * MAX;
$disp_data = array_slice($result, $start_no, MAX, true);

// ページネーション作成
$output_pagination = "";
$output_pagination .= "<div class='c-pagination'>";
$prev = max($now - 1, 1); // 前のページ番号
$next = min($now + 1, $max_page); // 次のページ番号

// 最初のページ以外で「前へ」を表示
if ($now != 1) {
  $output_pagination .= "<a class='prev' href='/diy_sns/home/?page={$prev}'>前へ</a>";
}
// ページネーションの本体
for ($i = 1; $i <= $max_page; $i++) {
  if ($i == $now) {
    $output_pagination .= "<span class='current'>{$now}</span>";
  } else {
    if (!isset($cat_param)) {
      $output_pagination .= "
        <a href='/diy_sns/home/?page={$i}')>{$i}</a>
      ";
    } else {
      $output_pagination .= "
        <a href='/diy_sns/home/?category={$cat_param}&page={$i}')>{$i}</a>
      ";
    }
  }
}
// 最後のページ以外で「次へ」を表示
if ($now < $max_page) {
  $output_pagination .= "<a class='next' href='/diy_sns/home/?page={$next}'>次へ</a>";
}
$output_pagination .= "</div>";

// -----------------------------
//  出力
// -----------------------------
$output_post = "";
if (!$result) {
  $output_post .= "
    <p>投稿はありませんでした</p>
    <p><a href='/diy_sns/home/'>みんなの投稿へ戻る</p>
  ";
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
      <h1>みんなの投稿</h1>
      <div class="post-sort">
        <?= $output_sort; ?>
      </div>
      <?= $output_cat_title; ?>
      <?= $output_post; ?>
      <div class="post-pagination">
        <?= $output_pagination; ?>
      </div>
    </div>
  </section>
</main>

<!-- footer -->
<?php include $path . "/common/footer.php"; ?>
<!-- //footer -->
