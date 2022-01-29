<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'] . "/diy_sns";
include $path . "/common/functions.php";
// セッションの有無をチェック
check_session_id("login");

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
  $uploaded_file_name = $_FILES['image']['name'];
  $temp_path  = $_FILES['image']['tmp_name'];
  // var_dump($temp_path);

  // ファイル名
  $upload_path = "/upload/";
  $directory_path = $_SERVER['DOCUMENT_ROOT'] . "/diy_sns" . $upload_path;

  $extension = pathinfo($uploaded_file_name, PATHINFO_EXTENSION);
  $unique_name = date('YmdHis') . md5(session_id()) . '.' . $extension;
  $save_path = $directory_path . $unique_name;
  $file_path = $upload_path . $unique_name;
  // var_dump($save_path);
  // exit();

  if (is_uploaded_file($temp_path)) {
    if (move_uploaded_file($temp_path, $save_path)) {
      chmod($save_path, 0644);
      echo $file_path;
      exit();
    } else {
      exit('Error:アップロードできませんでした');
    }
  } else {
    exit('Error:画像がありません');
  }
} else {
  exit("Error:画像の送信に失敗しました");
}
