<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'] . "/diy_sns";
include $path . "/common/functions.php";
?>

<!-- header -->
<?php include $path . "/common/header.php"; ?>
<!-- //header -->

<main>
  <section class="section">
    <div class="wrapper">
      <h1>登録完了</h1>
      <p>登録ありがとうございました</p>
      <a href="/diy_sns/login/">ログインページへ</a>
    </div>
  </section>
</main>

<!-- footer -->
<?php include $path . "/common/footer.php"; ?>
<!-- //footer -->
