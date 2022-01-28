<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>DIY_SNS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no">
  <?php include($path . "/common/seo.php"); ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/diy_sns/css/style.min.css">
</head>

<body>
  <!-- header -->
  <header class="header" id="js-header">
    <div class="header-wrapper">
      <h1 class="header__logo">
        <?php
        if (!isset($_SESSION["session_id"]) || $_SESSION["session_id"] != session_id()) {
          $home_url = "/diy_sns/";
        } else {
          $home_url = "/diy_sns/home/";
        }
        ?>
        <a href="<?= $home_url ?>">
          <img src="/diy_sns/img/common/logo.svg" alt="DIY SNS" width="120" height="18">
        </a>
      </h1>
      <nav class="header__nav">
        <ul class="g-nav">
          <?php if (!isset($_SESSION["session_id"]) || $_SESSION["session_id"] != session_id()) : ?>
            <li class="g-nav__item">
              <a href="/diy_sns/login/">ログイン</a>
            </li>
            <li class="g-nav__item">
              <a href="/diy_sns/register/">新規登録</a>
            </li>
          <?php else : ?>
            <li class="g-nav__item">
              <a class="g-nav__item__mypage" href="/diy_sns/user/dashboard/">
                <span><?= output_image_profile($_SESSION['image_profile']); ?></span>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>
  <!-- //header -->
