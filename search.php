<a href = "index.php"> 重新登入</a> <p>
<a href = "home.php">回到選單頁</a>

<?php
$studentID = $_COOKIE["studentID"];
echo '<br><br>您的學號：' . $studentID;
?>
<form action="search.php" method="post">
  輸入選課代碼：
  <input type='text' name='code' id='code' />
  <input type='submit' id='subCode' value='送出' data-action="submit"/>
</form>

<form action="search.php" method="post">
  輸入課程名稱：
  <input type='text' name='courseName' id='courseName' />
  <input type='submit' id='subCourseName' value='送出' data-action="submit"/>
</form>
<!--
<script type="text/javascript">
//   const submitBtn = document.querySelector('[data-action="submit"]');
//   submitBtn.addEventListener("click", processFormData);

// function processFormData(e) {
//   const codeElement = document.getElementById("code");
//   const code = codeElement.value;
// }
</script> -->

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

if (isset($_POST['code'])) {
    $code = $_POST['code'];
    $sql = "SELECT * FROM course where course_id = \"" . $code . "%\";";
    $result = mysqli_query($conn, $sql) or die('MySQL query error');
    if ($result->num_rows > 0) {
        echo '<table border="1">
      <tr>
        <td>course_id</td>
        <td>course_name</td>
        <td>class_name</td>
        <td>credit</td>
        <td>major</td>
        <td>section</td>
        <td>teacher_name</td>
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
            echo '</tr>';
        }
        echo '</table>';
        $sql = "SELECT * FROM course_time where course_id = \"" . $code . "%\";";
        $result = mysqli_query($conn, $sql) or die('MySQL query error');
        echo '上課日期：';
        while ($row = mysqli_fetch_array($result)) {
            echo '<p>星期' . $row['day'] . ' 第' . $row['time'] . '節<p>';
        }
    } else {
        echo '查無此課程';
    }
} else if (isset($_POST['courseName'])) {
    $courseName = $_POST['courseName'];
    $sql = "SELECT * FROM course where course_name like \"" . $courseName . "%\";";
    $result = mysqli_query($conn, $sql) or die('MySQL query error');
    if ($result->num_rows > 0) {
        echo '<table border="1">
      <tr>
        <td>course_id</td>
        <td>course_name</td>
        <td>class_name</td>
        <td>credit</td>
        <td>major</td>
        <td>section</td>
        <td>teacher_name</td>
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
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>查無此課程<p>';

    }
}

?>
