<?php
session_start();

header("Content-type: text/html; charset=UTF-8");
 
//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
if($_POST['token'] != $_SESSION['token']){
	echo "不正アクセスの可能性あり";
	exit();
}
 
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
 
//データベース接続
require_once("db.php");
$pdo = db_connect();
 
//エラーメッセージの初期化
$errors = array();
 
if(empty($_POST)){
	header("Location: mission_6-1.mail_regi_form1.php");
	exit();
}
 
$mail = $_SESSION['mail'];
$account = $_SESSION['account'];
$password = $_SESSION['password'];

//パスワードのハッシュ化
//$password_hash =  password_hash($_SESSION['password'], PASSWORD_DEFAULT);←サーバーのphpのバージョンに対応していない
 
//ここでデータベースに登録する
try{
	//例外処理を投げるようにする
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//トランザクション開始
	$pdo->beginTransaction();
	
	//memberテーブルに本登録する
	$statement = $pdo->prepare("INSERT INTO member (account,mail,password) VALUES (:account,:mail,:password)");
	//プレースホルダへ実際の値を設定する
	$statement->bindValue(':account', $account, PDO::PARAM_STR);
	$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
	$statement->bindValue(':password', $password, PDO::PARAM_STR);
	$statement->execute();
		
	//pre_memberのflagを1にする
	$statement = $pdo->prepare("UPDATE pre_member SET flag=1 where mail=(:mail)");
	//プレースホルダへ実際の値を設定する
	$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
	$statement->execute();

        //トランザクション完了（コミット）
	$pdo->commit();
		
	//データベース接続切断
	$pdo = null;
	
	//セッション変数を全て解除
	$_SESSION = array();
	
	//セッションクッキーの削除・sessionidとの関係を探れ。つまりはじめのsesssionidを名前でやる
	if(isset($_COOKIE["PHPSESSID"])){
    		setcookie("PHPSESSID", '', time() - 1800, '/');
	}
	
 	//セッションを破棄する
 	session_destroy();
 	
 	/*
 	登録完了のメールを送信
 	*/
	
}
catch(PDOException $e){
	//トランザクション取り消し（ロールバック）
	$pdo->rollBack();
	$errors['error'] = "もう一度やりなおして下さい。";
	print('Error:'.$e->getMessage());
}

?>
 
<!DOCTYPE html>
<html lang = "ja">

<head>
<title>会員登録完了画面</title>
<meta charset="UTF-8">
<style>
h1{
text-align: center;
font-family: '游ゴシック';
font-weight: normal;
border-bottom: dashed 2px #000066;
}

body{
color: navy;
text-align: center;
font-family: '游ゴシック';
font-size:25px;
}

.button:hover{
  border-style: dashed;
}

.square_btn {
    position: relative;
    display: inline-block;
    padding: 12px 0 8px;
    text-decoration: none;
    color: #000066;
    transition: .4s;
}
.square_btn:before{
   position: absolute;
   content: '';
   width: 100%;
   height: 4px;
   top:100%;
   left: 0;
   border-radius: 3px;
   background:#FF69B4;
   transition: .2s;
}
.square_btn:after{
   position: absolute;
   content: '';
   width: 100%;
   height: 4px;
   top:0;
   left: 0;
   border-radius: 3px;
   background:#DC143C;
   transition: .2s;
}
.square_btn:hover:before {
    top: -webkit-calc(100% - 3px);
    top: calc(100% - 3px);
}
.square_btn:hover:after {
    top: 3px;
}
</style>
</head>

<body>
 
<?php if(count($errors) === 0): ?>
<h1>会員登録完了画面</h1>
 
<p>登録完了いたしました。ログイン画面からどうぞ。</p>
<br>
<p><a href="mission_6-1.login6.php" class="square_btn">ログイン画面</a></p>
 
<?php elseif(count($errors) > 0): ?>
 
<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>
 
<?php endif; ?>
 
</body>

</html>