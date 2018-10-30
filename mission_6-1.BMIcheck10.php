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


//ログイン状態を維持したままBMIチェック

//テーブル作成
$sql = "CREATE TABLE BMI"
."("
."id INT auto_increment primary key,"
."height INT NOT NULL,"
."weight INT NOT NULL"
.");";
$stmt = $pdo->query($sql);

?>

<!DOCTYPE html>
<html lang = "ja">

<head>
<title>BMI計算</title>
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
  border: 3px solid #FF69B4;
  color: #DC143C;
  line-height: 40px;
  font-size:17px;
}
.button:hover{
  border-style: dashed;
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

.square_btn {
    position: relative;
    display: inline-block;
    padding: 5px 11px 5px 15px;
    text-decoration: none;
    color: #000066;
    transition: .4s;
    margin: 0 10px 0;
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
<h1>BMI Check</h1>

<form action="mission_6-1.BMIresult11.php" method="post">

<p>身長　<input type="text" name="height" class="text">　cm</p>
<p>体重　<input type="text" name="weight" class="text1">　kg</p>

<p><input type="submit" value="　計　算　" class = "button"></p>
<br>
<a href="mission_6-1.mail_regi_form1.php" class="square_btn">新規登録はこちら</a>
<a href="mission_6-1.login6.php" class="square_btn">ログインはこちら</a>
</form>

</body>

</html>