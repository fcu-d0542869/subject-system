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

$dbhost = '127.0.0.1';
$dbuser = 'test';
$dbpass = 'test1234';
$dbname = 'course';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
mysqli_query($conn, "SET NAMES 'utf8'");
mysqli_select_db($conn, $dbname);
$sql = "SELECT * FROM select_list where student_id like \"" . $studentID . "%\";";
$result = mysqli_query($conn, $sql) or die('MySQL query error');
$studentCourseID = array();
$studentCourseName = array();
while ($row = mysqli_fetch_array($result)) {
    array_push($studentCourseID, $row['course_id']);
}

if (isset($_POST['code'])) {
    $code = $_POST['code'];
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
    if ($result != null) {
        while ($row = mysqli_fetch_array($result)) {
            $selectCourse = $row['course_name'];
        }
        for ($i = 0; $i < count($studentCourseID); $i++) {
            if ($studentCourseID[$i] == $code) {
                $error = true;
                echo '已選該課程';
            }
        }
    }
    if ($error != true) {
        $sql = "SELECT * FROM select_list  NATURAL JOIN course where student_id like \"" . $studentID . "%\";";
        $result = mysqli_query($conn, $sql) or die('MySQL query error');
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

    }if ($sameTime != true && $error != true) {
        $sql = "SELECT day, time FROM select_list  NATURAL JOIN course_time where student_id like \"" . $studentID . "%\";";
        $result = mysqli_query($conn, $sql) or die('MySQL query error');
        while ($row = mysqli_fetch_array($result)) {
            array_push($dayTime, [$row['day'], $row['time']]);
        }
        $sql = "SELECT day, time FROM course_time where course_id = \"" . $code . "%\";";
        $result = mysqli_query($conn, $sql) or die('MySQL query error');
        while ($row = mysqli_fetch_array($result)) {
            array_push($codeTime, [$row['day'], $row['time']]);
        }
        if ($result != null) {
            for ($i = 0; $i < count($dayTime); $i++) {
                for ($j = 0; $j < count($codeTime[$j]); $j++) {
                    if ($dayTime[$i] == $codeTime[$j]) {
                        $sameTime = true;

                    }
                }
            }
            if ($sameTime == true) {
                echo '<p>衝堂<p>';
            }

        }

        // print_r($dayTime);
        // print_r($codeTime[0]);

    }
    if ($overCredit != true && $sameTime != true) {

        $sql = "SELECT sum(credit) as totalcredit FROM select_list  NATURAL JOIN course where student_id like \"" . $studentID . "%\";";
        $result = mysqli_query($conn, $sql) or die('MySQL query error');
        while ($row = mysqli_fetch_array($result)) {
            $totalCredit = $row['totalcredit'];
        }
        $sql = "SELECT credit FROM course where course_id = \"" . $code . "%\";";
        $result = mysqli_query($conn, $sql) or die('MySQL query error');
        while ($row = mysqli_fetch_array($result)) {
            $codeCredit = $row['credit'];
        }
        if ($totalCredit + $codeCredit > 30) {
            $overCredit = true;
            echo '<p>學分超過30學分<p>';
        }
    }

    if ($overSeat != true && $overCredit != true) {
        $sql = "SELECT seat,current_seat FROM seat where course_id = \"" . $code . "%\";";
        $result = mysqli_query($conn, $sql) or die('MySQL query error');
        while ($row = mysqli_fetch_array($result)) {
            if ($row['current_seat'] >= $row['seat']) {
                $overSeat = true;
                echo '<p>沒有位置了<p>';
            }
        }

    }

}

//     echo '<table border="1">
//   <tr>
//     <td>course_id</td>
//     <td>course_name</td>
//     <td>class_name</td>
//     <td>credit</td>
//     <td>major</td>
//     <td>section</td>
//     <td>teacher_name</td>
//   </tr>';
//     while ($row = mysqli_fetch_array($result)) {
//         echo '<tr>';
//         echo '<td>' . $row['course_id'] . '</td>';
//         echo '<td>' . $row['course_name'] . '</td>';
//         echo '<td>' . $row['class_name'] . '</td>';
//         echo '<td>' . $row['credit'] . '</td>';
//         echo '<td>' . $row['major'] . '</td>';
//         echo '<td>' . $row['section'] . '</td>';
//         echo '<td>' . $row['teacher_name'] . '</td>';
//         echo '</tr>';
//     }
//     echo '</table>';
//     $sql = "SELECT * FROM course_time where course_id = \"" . $code . "%\";";
//     $result = mysqli_query($conn, $sql) or die('MySQL query error');
//     echo '上課日期：';
//     while ($row = mysqli_fetch_array($result)) {
//         echo '<p>星期' . $row['day'] . ' 第' . $row['time'] . '節<p>';
//     }
// }
// else{
//   echo '查無此課程';

// }

?>
