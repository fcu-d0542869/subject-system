<?php
$dbhost = '127.0.0.1';
$dbuser = 'test';
$dbpass = 'test1234';
$dbname = 'course';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
mysqli_query($conn, "SET NAMES 'utf8'");
mysqli_select_db($conn, $dbname);

$studentID = $_COOKIE["studentID"];
echo $studentID;
$code=$_GET['code'];
echo $code;
$sql = "DELETE  FROM select_list  where course_id = \"" . $code . "\" AND student_id = \"" . $studentID . "\" ;";
$result = mysqli_query($conn, $sql) or die('MySQL query error');
echo '退選成功';
?>