<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'] . "/diy_sns";
include $path . "/common/functions.php";

// メールアドレス登録済みの場合
$status = $_GET["status"];
?>

<!-- header -->
<?php include $path . "/common/header.php"; ?>
<!-- //header -->

<main>
  <section class="section">
    <div class="wrapper">
      <h1>新規ユーザー登録</h1>
      <?php if ($status == "already_exist") : ?>
        <p style="color:red;">このメールアドレスはすでに登録されています</p>
      <?php endif; ?>
      <div class="login-form">
        <form action="./register.php" method="POST">
          <dl>
            <dt>ニックネーム</dt>
            <dd>
              <input type="text" name="name">
            </dd>
          </dl>
          <dl>
            <dt>メールアドレス</dt>
            <dd>
              <input type="email" name="email">
            </dd>
          </dl>
          <dl>
            <dt>パスワード</dt>
            <dd>
              <input type="password" name="password" autocomplete>
            </dd>
          </dl>
          <button class="c-submit" type="submit">登録</button>
        </form>
      </div>
      <a href="/diy_sns/login/">ログインはこちら</a>
    </div>
  </section>
</main>

<!-- footer -->
<?php include $path . "/common/footer.php"; ?>
<!-- //footer -->
