# 卒制プロトタイプ

## 卒制の概要

- 前回まで, DIYでわからないことをプロに聞ける, DIYerとメンターとのマッチングサービスを作っていた.
- 企画した当初から以下のような, 似たサービスが多く存在することは認識していた.
    - 職人とのマッチング
        - ivyCraft （https://ivycraft.jp/）
        - reHome （https://re-ho.me/）
    - 職人ではなくDIYの熟練者とのマッチング
        - Mr.DIY （https://mrdiy.net/）
    - 知恵袋的なサイト
        - DIY REPI （https://diyrepi.com/）
    - （こういうのもあった）
        - DIY レシピ（https://diy-recipe.com/）
    - （そもそもMENTAにもDIYカテゴリーがある）
        - MENTA （https://menta.work/bosyu?tag=&t=&q=&price=0&interval=0&categoryId=69）
- これらのサイトとの差別化を考え, 案を練ってきたが, どうしてもターゲットが「ニッチすぎ」になってしまうことが気になっており, 別のHow（What）も検討していた.
- そんな中, 前回の講義でファイルのアップロードを習ったのをヒントに, 別の企画を考えプロトタイプを作ってみた.

## 今回の企画

- 企画を一言で：リノベーション事例のBefore/After写真を投稿するSNS
- ユーザー：リノベーションを検討している人（特に, セルフリノベしたいが仕上がりや施工方法に悩んでいる人向け）
- 課題1：リノベーションのデザインや仕上がり,施工方法を検討するために,リノベーションの事例をよく調べる. しかし, Afterの写真がピックアップされることが多く, そこに至るまでの過程や自分の家で実現可能なのかが想像しにくい.
- 解決策：Before/After（+その過程）の写真投稿SNS
- 機能詳細
    - 基本的な機能はインスタグラムと似ているが, BeforeとAfterの写真が2枚必ず横並びになるように表示する
    - BeforeとAfterの写真はそれぞれ投稿には必須
    - よくあるハッシュタグ機能は無し, その代わりに施工場所別カテゴリー（リビング・キッチン・トイレなど）で絞り込みできるようにする
    - 後で見返せるようにLIKE機能はあり, マイページでアーカイブを作る
    - 投稿の詳細画面へ行くと, 制作過程の写真も複数枚見られるようにしたい
    - 使用したクロスやCF, 照明器具などの型番を記載できるような項目も設けたい（キーワード検索できるように）

## 伝達事項

- サンプルユーザーのパスワードは全て「password」です.

## 実装内容

- 前回まで作っていたマッチングサイトのコピーを元に進めた
- ログイン機能（ユーザーの属性は「一般ユーザー」「管理者」のみ）
- 投稿機能（画像のアップロード）
- プロフィール設定（名前やアイコン）
- カテゴリーの絞り込み検索機能

## 苦戦したこと

- 投稿画像とプロフィールアイコンは正方形でアップロードしたかったため, Cropper.jsを使用してトリミング機能をつけようとした
- 試みた作戦は次の流れ
    - input type=file で画像を選択
    - Cropper.jsでトリミング
    - base64データ（文字列）で戻ってくる
    - FILEの形に変換
    - FormDataにセット
    - ajaxでPOST送信
    - uploadフォルダへ画像をアップロード
    - 画像のファイル名を返す
    - ファイル名をinput type=textへセット
    - いつも通りDBへINSERT
- 現在も試行錯誤中だが, 1枚だけの処理は実装できた. 問題は複数枚... -> 2枚分の処理は一応できた
- input type=file で画像を選択してトリミングするたびに, アップロード処理が走るので, uploadフォルダが使われない画像で一杯になりそう.
- 動的に作成したformを送信することへの理解が足りておらず, 微妙なやり方してるんじゃね, と言う自覚はあるので, 複数の画像とニックネームや自己紹介文も含めて一度のPOSTで済むように修正していきたい.
- （こういうのはVue.jsが得意なのかな...?）

## 未着手

- ユーザーのプロフィールページ
- お気に入り機能
- 投稿アーカイブはページネーションにしているが, スクロールに連動した非同期読み込みにしたい
