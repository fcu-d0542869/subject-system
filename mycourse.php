<a href = "index.php"> 重新登入</a> <p>
<a href = "home.php">回到選單頁</a>

<?php
$studentID = $_COOKIE["studentID"];
echo '<br><br>您的學號：' . $studentID;

?>
<style>
table{
   	/* border-collapse: collapse; */
   	width: 1200px;
   	/*自動斷行*/
   	word-wrap: break-word;
   	table-layout: fixed;
   }
</style>

<?php
if ($studentID != null) {
    $dbhost = '127.0.0.1';
    $dbuser = 'test';
    $dbpass = 'test1234';
    $dbname = 'course';

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
    mysqli_query($conn, "SET NAMES 'utf8'");
    mysqli_select_db($conn, $dbname);

    $totalCredit=0;
    $sql = "SELECT  DISTINCT course_id ,credit FROM select_list  NATURAL JOIN course where student_id = \"" . $studentID . "\";";
    $result = mysqli_query($conn, $sql) or die('MySQL query error');
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $totalCredit =$totalCredit+$row['credit'];
            
        }
        echo '目前學分: '.$totalCredit;
    }

    $courseTime = "CREATE VIEW coursetime AS SELECT * FROM course  NATURAL JOIN course_time";
    // $result = mysqli_query($conn, $sql) or die('MySQL query error');

    $sql = "SELECT DISTINCT(course_id),course_name,class_name,credit,major,section,teacher_name,day,time FROM select_list  NATURAL JOIN coursetime  where student_id = \"" . $studentID . "\" ORDER BY course_id;";

    $result = mysqli_query($conn, $sql) or die('MySQL query error');
    echo '<table border="1">
    　　<tr>
    <td>course_id</td>
    <td>course_name</td>
    <td>class_name</td>
    <td>credit</td>
    <td>major</td>
    <td>section</td>
    <td>teacher_name</td>
    <td>day</td>
    <td>time</td>
    　</tr>';
    while ($row = mysqli_fetch_array($result)) {
        echo '<tr>';
        echo '<td>' . $row['course_id'] . '</td>';
        echo '<td>' . $row['course_name'] . '</td>';
        echo '<td>' . $row['class_name'] . '</td>';
        echo '<td>' . $row['credit'] . '</td>';
        echo '<td>' . $row['major'] . '</td>';
        echo '<td>' . $row['section'] . '</td>';
        echo '<td>' . $row['teacher_name'] . '</td>';
        echo '<td>' . $row['day'] . '</td>';
        echo '<td>' . $row['time'] . '</td>';
        echo '</tr>';

    }

    echo '</table>';

}

?>
