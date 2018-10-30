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

//データベースに接続
require_once("db.php");
$pdo = db_connect();

//エラーメッセージの初期化
$errors = array();

if(empty($_POST)){
	header("Location: mission_6-1.mail_regi_form1.php");
	exit();
}
else{
	//POSTされたデータを変数に入れる
	$mail = isset($_POST['mail']) ? $_POST['mail'] : NULL;//←null(空欄)で登録してもいいフィールドができた

	//メール入力判定
	if($mail == ''){
		$errors['mail'] = "メールアドレスが入力されていません。";
	}
	else{
		if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail)){
			$errors['mail_check'] = "メールアドレスの形式が正しくありません。";
		}
	}
}

if(count($errors) === 0){
	$urltoken = hash('sha256',uniqid(rand(),1));
	$url = "http://tt-302.99sv-coco.com/mission_6-1.regi_form3.php"."?urltoken=".$urltoken;
	
	//ここでデータベースに登録する
	try{
		//例外処理を投げるようにする
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$statement = $pdo->prepare("INSERT INTO pre_member (urltoken,mail,date) VALUES (:urltoken,:mail,now())");
		
		//プレースホルダへ実際の値を設定する
		$statement->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
		$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
		$statement->execute();
		
		//データベース接続切断
		$pdo = null;
	}
	catch(PDOException $e){
		print('Error:'.$e->getMessage());
		die();
	}

	//メールの宛先
	$mailTo = $mail;
	//Return-Pathに指定するメールアドレス
	$returnMail = 'メールアドレス';

	$name = "Check Your Body!";
	$mail = 'メールアドレス';
	$subject = "【Check Your Body!】会員登録用URLのお知らせ";

$body = <<< EOM
24時間以内に下記のURLからご登録下さい。
{$url}
EOM;

	mb_language('ja');
	mb_internal_encoding('UTF-8');

	//Fromヘッダーを作成
	$header = 'From: ' . mb_encode_mimeheader($name). ' <' . $mail. '>';

	if(mb_send_mail($mailTo, $subject, $body, $header, '-f'. $returnMail)){

	 	//セッション変数を全て解除
		$_SESSION = array();

		//クッキーの削除
		if(isset($_COOKIE["PHPSESSID"])){
			setcookie("PHPSESSID", '', time() - 1800, '/');
		}
	
 		//セッションを破棄する
 		session_destroy();
 	
 		$message = "メールをお送りしました。24時間以内にメールに記載されたURLからご登録下さい。";
	}
	else{
		$errors['mail_error'] = "メールの送信に失敗しました。";
	}
}
?>

<!DOCTYPE html>
<html lang = "ja">

<head>
<title>メール確認画面</title>
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
  border: 2px solid #DC143C;
  color: #000066;
  line-height: 40px;
}
.button:hover{
  border-style: dashed;
}

</style>
</head>

<body>
<h1>メール確認画面</h1>
 
<?php if(count($errors) === 0): ?>

<p><?=$message?></p>
 
<p>↓このURLが記載されたメールが届きます。</p>
<a href="<?=$url?>"><?=$url?></a>
 
<?php elseif(count($errors) > 0): ?>
 
<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>
 
<input type="button" value="　戻　る　"  class = "button" onClick="history.back()">
 
<?php endif; ?>
 
</body>

</html>