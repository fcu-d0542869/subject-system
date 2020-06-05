<a href = "index.php"> 重新登入</a> <p>
<?php
if (isset($_POST['studentID'])) {
    $studentID = $_POST['studentID'];
    setcookie("studentID", $studentID, time() + 3600);
}

echo '您的學號：' . $_COOKIE["studentID"];
if ($_COOKIE["studentID"] != null) {
    $dbhost = '127.0.0.1';
    $dbuser = 'test';
    $dbpass = 'test1234';
    $dbname = 'course';
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');

    mysqli_query($conn, "SET NAMES 'utf8'");
    mysqli_select_db($conn, $dbname);
    $sql = "SELECT * FROM coursetime";
    $result = mysqli_query($conn, $sql) or die('MySQL query error');
    if ($result == null) {
        $courseTime = "CREATE VIEW coursetime AS SELECT * FROM course  NATURAL JOIN course_time";
        mysqli_query($conn, $courseTime) or die('MySQL query error');
    }
}
?>

<br><a href="search.php">查詢選課資訊</a>
<br><a href="mycourse.php">我的課表</a>
<br><a href="joincourse.php">選課</a>
<br><a href="unselect.php">退選</a>







