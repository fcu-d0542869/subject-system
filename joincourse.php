<h1>選課頁面</h1>
<a href = "index.php"> 重新登入</a> <p>
<a href = "home.php">回到選單頁</a>

<?php
$studentID = $_COOKIE["studentID"];
echo '<br><br>您的學號：' . $studentID;
?>
<form action="joincourse.php" method="post">
  輸入選課代碼：
  <input type='text' name='code' id='code' />
  <input type='submit' id='subCode' value='送出' data-action="submit"/>
</form>

<style>
table{
   	/* border-collapse: collapse; */
   	width: 900px;
   	/*自動斷行*/
   	word-wrap: break-word;
   	table-layout: fixed;
   }
</style>

<?php
if (isset($_POST['code'])) {
    $code = $_POST['code'];
    $dbhost = '127.0.0.1';
    $dbuser = 'test';
    $dbpass = 'test1234';
    $dbname = 'course';
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
    mysqli_query($conn, "SET NAMES 'utf8'");
    mysqli_select_db($conn, $dbname);
    $sql = "SELECT * FROM select_list where student_id = \"" . $studentID . "\";";
    $result = mysqli_query($conn, $sql) or die('MySQL query error');
    $studentCourseID = array();
    $studentCourseName = array();
    while ($row = mysqli_fetch_array($result)) {
        array_push($studentCourseID, $row['course_id']);
    }
    for ($i = 0; $i < count($studentCourseID); $i++) {
        if ($studentCourseID[$i] == $code) {
            $error = true;
            echo '已選該課程';
        }
    }
    $error = false;
    $sameTime = false;
    $overCredit = false;
    $overSeat = false;
    $codeTime = array();
    $dayTime = array();
    $totalCredit = 0;
    $codeCredit = 0;
    $selectCourse = null;
    $sql = "SELECT course_name FROM course where course_id = \"" . $code . "%\";";
    $result = mysqli_query($conn, $sql) or die('MySQL query error');
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $selectCourse = $row['course_name'];
        }

    } else {
        $error = true;
        echo '查無此課程';
    }
    if ($error != true) {
        $sql = "SELECT course_name FROM select_list  NATURAL JOIN course where student_id = \"" . $studentID . "\";";
        $result = mysqli_query($conn, $sql) or die('MySQL query error');
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {
                // echo '<p>'.$row['course_name'].'<p>';
                array_push($studentCourseName, $row['course_name']);
            }
            for ($i = 0; $i < count($studentCourseName); $i++) {
                if ($studentCourseName[$i] == $selectCourse) {
                    $error = true;
                    echo '已選相同名稱之課程';
                }
            }
        }

    }if ($sameTime != true && $error != true) {
        $sql = "SELECT day, time FROM select_list  NATURAL JOIN course_time where student_id = \"" . $studentID . "\";";
        $result = mysqli_query($conn, $sql) or die('MySQL query error');
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {
                array_push($dayTime, [$row['day'], $row['time']]);
            }
        }
        $sql = "SELECT day, time FROM course_time where course_id = \"" . $code . "%\";";
        $result = mysqli_query($conn, $sql) or die('MySQL query error');
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {
                array_push($codeTime, [$row['day'], $row['time']]);
            }
        }
        for ($i = 0; $i < count($dayTime); $i++) {
            for ($j = 0; $j < count($codeTime); $j++) {
                if ($dayTime[$i] == $codeTime[$j]) {
                    $sameTime = true;
                }
            }
        }
        if ($sameTime == true) {
            echo '<p>衝堂<p>';
        }

        //  print_r($dayTime);
        //print_r($codeTime);

    }
    if ($overCredit != true && $sameTime != true && $error != true) {

        $sql = "SELECT  DISTINCT course_id ,credit FROM select_list  NATURAL JOIN course where student_id = \"" . $studentID . "\";";
        $result = mysqli_query($conn, $sql) or die('MySQL query error');
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $totalCredit = $totalCredit + $row['credit'];
            }
        }
        $sql = "SELECT credit FROM course where course_id = \"" . $code . "%\";";
        $result = mysqli_query($conn, $sql) or die('MySQL query error');
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $codeCredit = $row['credit'];
            }
        }
        if ($totalCredit + $codeCredit > 30) {
            $overCredit = true;
            echo '<p>學分超過30學分<p>';
        }
    }

    if ($error != true && $sameTime != true && $overCredit != true && $overSeat != true) {
        $sql = "SELECT seat,current_seat FROM seat where course_id = \"" . $code . "%\";";
        if ($result->num_rows > 0) {
            $result = mysqli_query($conn, $sql) or die('MySQL query error');
            while ($row = mysqli_fetch_array($result)) {
                if ($row['current_seat'] >= $row['seat']) {
                    $overSeat = true;
                    echo '<p>沒有位置了<p>';
                }
            }
        }
    }

    if ($error != true && $sameTime != true && $overCredit != true && $overSeat != true) {
        $sql = "INSERT INTO select_list VALUES ('$studentID','$code');";
        $result = mysqli_query($conn, $sql) or die('MySQL query error');
        echo '<p>選課成功<p>';
    }

}

?>
