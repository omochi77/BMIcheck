<?php
session_start();
 
header("Content-type: text/html; charset=utf-8");
 
// ログイン状態のチェック
if(!isset($_SESSION["account"])){
	header("Location: mission_6-1.login6.php");
	exit();
}
 
$account = $_SESSION['account'];

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

span{
color: #DC143C;
font-weight: bold;
font-size:30px;
}

</style>
</head>

<body>
<?php
echo "<span>".$account."</span>さん、こんにちは！<br><br><br>";

echo "<a href='mission_6-1.BMIcheck10.php' class='square_btn'>BMICheck</a>";

echo "<a href='mission_6-1.logout9.php' class='square_btn1'>LOGOUT</a>";
?>
</body>
</html>