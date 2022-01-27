<?php

// -----------------------------
//  DB接続
// -----------------------------
function connect_to_db()
{
  $dbn = 'mysql:dbname=diy_sns;charset=utf8mb4;port=3306;host=localhost';
  $user = 'root';
  $pwd = '';
  try {
    return new PDO($dbn, $user, $pwd);
  } catch (PDOException $e) {
    exit('dbError:' . $e->getMessage());
  }
}

// -----------------------------
//  SESSION関連
// -----------------------------
// ログイン状態のチェック
// 引数はリダイレクト先
function check_session_id($url)
{
  if (!isset($_SESSION["session_id"]) || $_SESSION["session_id"] != session_id()) {
    header("Location:/diy_sns/{$url}");
    exit();
  } else {
    session_regenerate_id(true);
    $_SESSION["session_id"] = session_id();
  }
}

// セッション破棄
function delete_session()
{
  $_SESSION = array();
  if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
  }
  session_destroy();
}

// -----------------------------
// 404 へのリダイレクト
// -----------------------------
function error404()
{
  http_response_code(404);
  include $_SERVER['DOCUMENT_ROOT'] . "/diy_sns/404.php";
  exit();
}

// -----------------------------
//  出力に使う関数
// -----------------------------
// 日付フォーマットの変更
function datetime_to_ymd($datetime)
{
  $created_datetime = new DateTime($datetime);
  return $created_datetime->format('Y-m-d');
}

// カテゴリーの出力
function output_category($data)
{
  $category_list = "";
  if (!empty($data)) {
    $record_category = explode(",", $data);
    $category_list .= "<div class='c-category'>";
    foreach ($record_category as $val) {
      $category_list .= "
        <span>{$val}</span>
      ";
    }
    $category_list .= "</div>";
  } else {
    $category_list = "";
  }
  return $category_list;
}

// 投稿リストの雛形
function output_post_list($data)
{
  $output_post_list = "";
  $output_post_list .= "<ul class='p-list'>";
  foreach ($data as $record) {
    // カテゴリー
    $category_list = output_category($record["category"]);

    $output_post_list .= "
      <li class='p-list__item'>
        <a class='p-list__item__link' href='/diy_sns/home/single.php?post_id={$record["id"]}'>
          <div class='p-list-thumb'>
            <div class='p-list-thumb__item'>
              <img src='/diy_sns{$record["image_before"]}' width='500' height='500'>
            </div>
            <div class='p-list-thumb__item'>
             <img src='/diy_sns{$record["image_after"]}' width='500' height='500'>
            </div>
          </div>
          <div class='p-list-meta'>
            <div class='p-list-meta__item'>
              <a href='#'><img src='#'></a>
            </div>
          </div>
          <div class='p-list-text'>
            {$category_list}
            <h3 class='p-list-text__title'>{$record["title"]}</h3>
          </div>
        </a>
      </li>
    ";
  }
  $output_post_list .= "</ul>";
  return $output_post_list;
}

// -----------------------------
//  FILE 受け取り
// -----------------------------
function post_file($name, $max_file_size)
{
  if (isset($_FILES[$name]) && $_FILES[$name]['error'] == 0) {
    $uploaded_file_name = $_FILES[$name]['name'];
    $temp_path  = $_FILES[$name]['tmp_name'];
    $upload_path = "/upload/";
    $directory_path = $_SERVER['DOCUMENT_ROOT'] . "/diy_sns" . $upload_path;
    $extension = pathinfo($uploaded_file_name, PATHINFO_EXTENSION);
    $random_number = md5(uniqid(rand(), true));
    $unique_name = date('YmdHis') . md5(session_id()) . $random_number . '.' . $extension;
    $save_path = $directory_path . $unique_name;
    $file_path = $upload_path . $unique_name;

    // ファイルサイズ
    $file_size = $_FILES[$name]['size'];

    if (is_uploaded_file($temp_path)) {
      // ファイルサイズの制限
      if ($file_size < $max_file_size) {
        if (move_uploaded_file($temp_path, $save_path)) {
          chmod(
            $save_path,
            0644
          );
        } else {
          exit('Error:アップロードできませんでした');
        }
      } else {
        exit('Error:ファイルのサイズが大きすぎます');
      }
    } else {
      exit('Error:画像がありません');
    }
  } elseif (isset($_FILES[$name]) && $_FILES[$name]['error'] == 4) {
    $file_path = null;
  } else {
    exit("Error:画像の送信に失敗しました");
  }
  return $file_path;
}
