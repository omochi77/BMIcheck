<?php
session_start();

header("Content-type: text/html; charset=UTF-8");//

//クロスサイトリクエストフォージェリ（CSRF）対策
/*重要な処理を行うページに、hidden 項目(見えない項目)でトークンという秘密情報をセットして表示。
このとき、セッション情報としてトークンを保存しておく。*/
//$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(26));//
$token = $_SESSION['token'];
 
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');//

?>
<!DOCTYPE html>
<html lang = "ja">

<head>
<title>メール登録画面</title>
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
font-family '游ゴシック';
font-size:25px;
}

.button{
  background-color: #fff;
  border: 3px solid #DC143C;
  color: #000066;
  line-height: 40px;
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
width:300px;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
}
</style>
</head>

<body>
<h1>メール登録画面</h1>

<form action = "mission_6-1.mail_regi_check2.php" method = "post">
<p>メールアドレス：<input type = "text" name = "mail" size = "40" class="text"></p>
<input type = "hidden" name = "token" value = "<?=$token?>">

<input type = "submit" value = "　登　録　" class = "button">
</form>
<br>
<br>
<a href="mission_6-1.login6.php" class="square_btn">会員登録お済みの方はこちら</a>

</body>
</html>