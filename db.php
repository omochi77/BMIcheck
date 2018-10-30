<?php
function db_connect(){
	$dsn = 'データベース名';
	$user = 'ユーザ名';
	$password = 'パスワード';
	
	try{
		//DBへ接続
		$pdo = new PDO($dsn, $user, $password);
		
		//SQL作成
		$sql = "CREATE TABLE pre_member"
		."(" 
		."id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
		."urltoken VARCHAR(128) NOT NULL,"
		."mail VARCHAR(50) NOT NULL,"
		."date DATETIME NOT NULL,"
		."flag TINYINT(1) NOT NULL DEFAULT 0"
		.");";
		
		$sql = "CREATE TABLE member"
		."(" 
		."id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
		."account VARCHAR(50) NOT NULL,"
		."mail VARCHAR(50) NOT NULL,"
		."password VARCHAR(128),"
		."flag TINYINT(1) NOT NULL DEFAULT 1"
		.");";
		
		//SQL実行
		$statement = $pdo->query($sql);
		
		return $pdo;
	}
	catch (PDOException $e){
	    	print('Error:'.$e->getMessage());
	    	die();
	}
}

?>
