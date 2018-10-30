<?php
session_start();
 
header("Content-type: text/html; charset=utf-8");
 
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
 
//前後にある半角全角スペースを削除する関数
function spaceTrim ($str){
	// 行頭
	$str = preg_replace('/^[ 　]+/u', '', $str);
	// 末尾
	$str = preg_replace('/[ 　]+$/u', '', $str);
	return $str;
}
 
//エラーメッセージの初期化
$errors = array();
 
if(empty($_POST)){
	header("Location: mission_6-1.login6.php");
	exit();
}
else{
	//POSTされたデータを各変数に入れる
	$account = isset($_POST['account']) ? $_POST['account'] : NULL;
	$password = isset($_POST['password']) ? $_POST['password'] : NULL;
	
	//前後にある半角全角スペースを削除
	$account = spaceTrim($account);
	$password = spaceTrim($password);
 
	//アカウント入力判定
	if($account == ''):
		$errors['account'] = "アカウントが入力されていません。";
	elseif(mb_strlen($account)>10):
		$errors['account_length'] = "アカウントは10文字以内で入力して下さい。";
	endif;
	
	//パスワード入力判定
	if($password == ''):
		$errors['password'] = "パスワードが入力されていません。";
	elseif(!preg_match('/^[0-9a-zA-Z]{5,30}$/', $_POST["password"])):
		$errors['password_length'] = "パスワードは半角英数字の5文字以上30文字以下で入力して下さい。";
	else:
		$password_hide = str_repeat('*', strlen($password));
	endif;
	
}
//エラーが無ければ実行する
if(count($errors) === 0){
	try{
		//例外処理を投げる（スロー）ようにする
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		//アカウントで検索
		$statement = $pdo->prepare("SELECT * FROM member WHERE account=(:account) AND flag =1");
		$statement->bindValue(':account', $account, PDO::PARAM_STR);
		

		//アカウントが一致
		if($statement->execute()){
 			foreach($statement as $row){
			$_SESSION['password'] = $row['password'];
			echo $_SESSION['password'];
			}
			//パスワードが一致
			if($password = $_SESSION['password']){
				
				//セッションハイジャック対策
				session_regenerate_id(true);
				
				$_SESSION['account'] = $account;
				header("Location: mission_6-1.login_admin8.php");
				exit();
			}else{
				$errors['password'] = "パスワードが一致しません。";
			}
		}else{
			$errors['account'] = "アカウントが一致しません。";
		}
		
		//データベース接続切断
		$pdo = null;
		
	}catch(PDOException $e){
		print('Error:'.$e->getMessage());
		die();
	}
}
 
?>
 
<!DOCTYPE html>
<html lang = "ja">

<head>
<title>ログイン確認画面</title>
<meta charset="utf-8">
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
</style>
</head>

<body>
<h1>ログイン確認画面</h1>
 
<?php if(count($errors) > 0): ?>
 
<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>
 
<input type="button" value="　戻　る　" class = "button" onClick="history.back()">
 
<?php endif; ?>
 
</body>

</html>