<?php
session_start();
 
header("Content-type: text/html; charset=UTF-8");
 
//クロスサイトリクエストフォージェリ（CSRF）対策
//$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];
 
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
 
//データベース接続
require_once("db.php");
$pdo = db_connect();
 
//エラーメッセージの初期化
$errors = array();
 
if(empty($_GET)){
	header("Location: mission_6-1.mail_regi_form1.php");
	exit();
}
else{
	//GETデータを変数に入れる
	$urltoken = isset($_GET[urltoken]) ? $_GET[urltoken] : NULL;//←null(空欄)で登録してもいいフィールドができた

	//メール入力判定
	if($urltoken == ''){
		$errors['urltoken'] = "もう一度登録をやりなおして下さい。";
	}
	else{
		try{
			//例外処理を投げるようにする
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			//flagが0の未登録者・仮登録日から24時間以内
			$statement = $pdo->prepare("SELECT mail FROM pre_member where urltoken=(:urltoken) AND flag =0 AND date > now() - interval 24 hour");
			$statement->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
			$statement->execute();

			//レコード件数取得
			$row_count = $statement->rowCount();
			
			//24時間以内に仮登録され、本登録されていないトークンの場合
			if($row_count ==1){
				$mail_array = $statement->fetch();
				$mail = $mail_array[mail];
				$_SESSION['mail'] = $mail;
			}
			else{
				$errors['urltoken_timeover'] = "このURLはご利用できません。有効期限が過ぎた等の問題があります。もう一度登録をやりなおして下さい。";
			}
			
			//データベース接続切断
			$pdo = null;
			
		}
		catch(PDOException $e){
			print('Error:'.$e->getMessage());
			die();
		}
	}
}
 
?>

<!DOCTYPE html>
<html lang = "ja">

<head>
<title>会員登録画面</title>
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
<h1>会員登録画面</h1>
 
<?php if(count($errors) === 0): ?>
 
<form action="mission_6-1.regi_check4.php" method="post">
 
<p>メールアドレス　　<?=htmlspecialchars($mail, ENT_QUOTES, 'UTF-8')?></p>
<p>アカウント名　　<input type="text" name="account" class="text"></p>
<p>パスワード　　　<input type="text" name="password" class="text1"></p>

<input type="hidden" name="token" value="<?=$token?>">
<input type="submit" value="　確　認　" class = "button">
 
</form>
 
<?php elseif(count($errors) > 0): ?>
 
<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>
 
<?php endif; ?>
 
</body>

</html>