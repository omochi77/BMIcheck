<?php
session_start();
 
header("Content-type: text/html; charset=utf-8");
 
// ログイン状態のチェック
if(!isset($_SESSION["account"])) {
	header("Location: mission_6-1.login6.php");
	exit();
}
 
//セッション変数を全て解除
$_SESSION = array();
 
//セッションクッキーの削除
if(isset($_COOKIE["PHPSESSID"])) {
	setcookie("PHPSESSID", '', time() - 1800, '/');
}
 
//セッションを破棄する
session_destroy();
 
echo "<p>ログアウトしました</p><br>";

?>
<!DOCTYPE html>
<html lang = "ja">

<head>
<meta charset="UTF-8">
<style>
body{
color: navy;
text-align: center;
font-family: '游ゴシック';
font-size:25px;
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

p{
color: #00008B;
font-weight: bold;
}

</style>
</head>

<body>
<?php

echo "<a href='mission_6-1.login6.php' class='square_btn'>ログイン画面へ</a>";

?>
</body>
</html>