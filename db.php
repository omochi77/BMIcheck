<?php
function db_connect(){
	$dsn = '�f�[�^�x�[�X��';
	$user = '���[�U��';
	$password = '�p�X���[�h';
	
	try{
		//DB�֐ڑ�
		$pdo = new PDO($dsn, $user, $password);
		
		//SQL�쐬
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
		
		//SQL���s
		$statement = $pdo->query($sql);
		
		return $pdo;
	}
	catch (PDOException $e){
	    	print('Error:'.$e->getMessage());
	    	die();
	}
}

?>
