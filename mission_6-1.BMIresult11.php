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

//POSTで身長・体重値を受け取る
$height = $_POST['height'];
$weight = $_POST['weight'];

$sql = $pdo -> prepare("INSERT INTO BMI(height,weight) VALUES (:height,:weight)");
$sql -> bindParam(':height',$height,PDO::PARAM_STR);
$sql -> bindParam(':weight',$weight,PDO::PARAM_STR);
$sql -> execute();//実行

?>

<!DOCTYPE html>
<html lang = "ja">

<head>
<title>BMI結果</title>
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

.square_btn {
    position: relative;
    display: inline-block;
    padding: 12px 0 8px;
    text-decoration: none;
    color: #000066;
    transition: .4s;
    margin: 0 10px 0;
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

.square_btn1 {
    position: relative;
    display: inline-block;
    padding: 12px 0 8px;
    text-decoration: none;
    color: #000066;
    transition: .4s;
    margin: 0 10px 0;
}
.square_btn1:before{
   position: absolute;
   content: '';
   width: 100%;
   height: 4px;
   top:100%;
   left: 0;
   border-radius: 3px;
   background:#DC143C;
   transition: .2s;
}
.square_btn1:after{
   position: absolute;
   content: '';
   width: 100%;
   height: 4px;
   top:0;
   left: 0;
   border-radius: 3px;
   background:#FF69B4;
   transition: .2s;
}
.square_btn1:hover:before {
    top: -webkit-calc(100% - 3px);
    top: calc(100% - 3px);
}
.square_btn1:hover:after {
    top: 3px;
}

.square_btn2 {
    position: relative;
    display: inline-block;
    padding: 12px 0 8px;
    text-decoration: none;
    color: #000066;
    transition: .4s;
    margin: 0 10px 0;
}
.square_btn2:before{
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
.square_btn2:after{
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
.square_btn2:hover:before {
    top: -webkit-calc(100% - 3px);
    top: calc(100% - 3px);
}
.square_btn2:hover:after {
    top: 3px;
}

p1{
color: #DC143C;
font-weight: bold;
}
p2{
color: #FF69B4;
font-weight: bold;
}
</style>
</head>

<body>
<h1>BMI結果</h1>

<form action="mission_6-1.BMIresult11.php" method="post">

<p2><?php echo $_SESSION['account']; ?></p2>さんのBMI指数は、
<?php 
//BMI指数を計算
$sql = "SELECT id, weight / ((height/100) * (height/100)) as bmi FROM BMI ORDER BY id DESC LIMIT 1";
$results = $pdo -> query($sql)->fetchAll(PDO::FETCH_ASSOC);
foreach($results as $row){
echo '<p1>'.round($row['bmi'],1).'</p1>'; } ?>で、
<?php
if($row['bmi']<18.5){
	echo '<p1>'.低体重.'</p1>'.です;
}
elseif($row['bmi']>=18.5 && $row['bmi']<25){
	echo '<p1>'.普通.'</p1>'.です;
}
else{
	echo '<p1>'.肥満気味.'</p1>'.です;
}
?>
<br>
標準・理想体重は、
<?php
//標準・理想体重を計算
$sql = "SELECT id, (height * height * 22/10000) as ideal FROM BMI ORDER BY id DESC LIMIT 1";
$results = $pdo -> query($sql)->fetchAll(PDO::FETCH_ASSOC);
foreach($results as $row){
echo  '<p1>'.round($row['ideal'],1).'</p1>'; } ?>kgです
<br>
<br>
<br>
(※) BMI値が全てではありません。<br>
あくまで健康維持の目安としてください。

</form>
<br>
<a href="mission_6-1.mail_regi_form1.php" class="square_btn">TOPPAGE</a>
<a href="mission_6-1.login6.php" class="square_btn1">LOGIN</a>
<a href="mission_6-1.BMIcheck10.php" class="square_btn2">BMICheck</a>
</body>

</html>