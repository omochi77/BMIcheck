<?php
session_start();//php記述の一番最初にもってくる
 
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

$informations = array();//変数$informationsとして空の配列を定義する

//ログイン画面で入力したメールアドレスとパスワードをPOSTで受け取る
if($_POST){
	$login_mail = $_POST['login_mail'];
	$login_pass = $_POST['login_pass'];

	/*テーブル内にメールアドレスとパスワードが一致するユーザーがいる場合、
	登録しているユーザーの名前とidを取ってくる。
	この時点で、1で定義した変数$informationsは空の配列ではなく、
	データを持ったものとなる*/
	$sql = "SELECT * FROM informations WHERE mail = $login_mail AND pass = $login_pass"; 

	$informations = $pdo->fetch($sql);

	/*$informationsが中身を持っている場合、
	セッション情報としてユーザーの名前とidを保存する*/
	if(count($informations) > 0){
		$_SESSION['name'] = $informations[0]['name'];
		$_SESSION['id'] = $informations[0]['id'];
	}
}

//最後にセッション情報を持っていればTOPページに移動
if($_SESSION['id'] > 0){
	header("Location: mission_6-1.mail_regi_form1.php");//トップページどこにするか？
}


$sql = "CREATE TABLE informations"
."("
."id INT auto_increment primary key,"
."name char(32),"
."mail TEXT,"
."pass TEXT"
.");";
$stmt = $pdo->query($sql);

?>

<!DOCTYPE html>
<html lang = "ja">

<head>
<title>ログイン画面</title>
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

.button{
  background-color: #fff;
  border: 3px solid #DC143C;
  color: #000066;
  line-height: 40px;
  font-size:15px;
}
.button:hover{
  border-style: dashed;
}

.square_btn {
    position: relative;
    display: inline-block;
    padding: 5px 11px 5px 15px;
    text-decoration: none;
    color: #000066;
    transition: .4s;
}

.square_btn:before {
   position: absolute;
   display: inline-block;
   content: '';
   width: 4px;
   height: 100%;
   top: 0;
   left: 0;
   border-radius: 3px;
   background:#FF69B4;
}

.square_btn:after{
   position: absolute;
   display: inline-block;
   content: '';
   width: 4px;
   height: 100%;
   top:0;
   left: 100%;
   border-radius: 3px;
   background:#DC143C;
}

.square_btn:hover:before,.square_btn:hover:after{
  -webkit-transform: rotate(10deg);
  -ms-transform: rotate(10deg);
  transform: rotate(10deg);
}
.text{
border:0;
padding:0.25px;
font-size:1em;
font-family:Arial, sans-serif;
color:#aaa;
border:solid 1px #00BFFF;
margin:0 0 10px;
width:200px;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
}
.text1{
border:0;
padding:0.25px;
font-size:1em;
font-family:Arial, sans-serif;
color:#aaa;
border:solid 1px #00BFFF;
margin:0 0 10px;
width:200px;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
}
</style>
</head>

<body>
<h1>Check Your Body!</h1>

<form action="mission_6-1.login_check7.php" method="post">

<p>ID  　　　<input type="text" name="account" class="text"></p>
<p>PASS　　<input type="text" name="password" class="text1"></p>

<input type="submit" value="　L O G I N　" class = "button">
<br>
<br>
<p><a href="mission_6-1.mail_regi_form1.php" class="square_btn">新規登録はこちら</a></p>

</form>

</body>

</html>
