<h1>退選頁面</h1>
<a href="index.php"> 重新登入</a>
<br>
<a href="home.php">回到選單頁</a>

<?php
$studentID = $_COOKIE["studentID"];
echo '<br><br>您的學號：' . $studentID;
?>

<script>

  function warning () {
    var xhttp;
    xhttp = new XMLHttpRequest();
    if(confirm('退選此課程是必修課，確定要退選嗎？')) {
      console.log('true');
      xhttp.open("GET", "major.php?code="+code, true);
      console.log(code);
      xhttp.send(null);
      alert('退選成功');
    } else {
        alert('退選失敗');
    //   console.log('false');
    }

  }
</script>
    <form action="" method="post">
        輸入選課代碼：
        <input type='text' name='code' id='code' />
        <input type='submit'id='subCode' value='送出'  onclick="console.log();" data-action="submit" />
    </form>

    <style>
        table {
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

$sql = "SELECT * FROM select_list where student_id = \"" . $studentID . "\";";
$result = mysqli_query($conn, $sql) or die('MySQL query error');
$studentCourseID = array();
$studentCourseName = array();
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        array_push($studentCourseID, $row['course_id']);
    }
}
if (isset($_POST['code'])) {
    $code = $_POST['code'];

    $totalCredit = 0;
    $codeCredit = 0;
    $underCredit = false;
    $mycourse = array();
    $noJoin = false;
    $codeClass = array();
    $className = null;
    $noCourse = false;
    $isMajor = false;

    $sql = "SELECT  DISTINCT course_id ,credit FROM select_list  NATURAL JOIN course where student_id = \"" . $studentID . "\";";
    $result = mysqli_query($conn, $sql) or die('MySQL query error');
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_array($result)) {
            array_push($mycourse, $row['course_id']);
            $totalCredit = $totalCredit + $row['credit'];
        }
    }
    $sql = "SELECT credit, class_name ,major FROM course where course_id = \"" . $code . "%\";";
    $result = mysqli_query($conn, $sql) or die('MySQL query error');
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_array($result)) {
            array_push($codeClass, [$row['class_name'], $row['major']]);
            $codeCredit = $row['credit'];
        }

    } else {
        $noCourse = true;
        echo '<p>查無此課程<p>';
    }

    if ($noCourse != true) {
        if (!in_array($code, $mycourse)) {
            echo '你沒有選此課程';
            $noJoin = true;
        }
        if ($noJoin != true) {
            if ($totalCredit - $codeCredit < 9 && $codeCredit > 0 && $totalCredit > 0) {
                $underCredit = true;
                echo '<p>退選後學分低於9學分<p>';
            }

            if ($underCredit != true && $noJoin != true) {
                $sql = "SELECT class_name FROM student  where student_id = \"" . $studentID . "\";";
                $result = mysqli_query($conn, $sql) or die('MySQL query error');

                if ($result->num_rows > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        $className = $row['class_name'];
                    }
                    for ($i = 0; $i < count($codeClass); $i++) {
                        if ($className == $codeClass[$i][0] && $codeClass[$i][1] == 'M') {

                            echo '<script> var code =' . $code .';warning();</script>';
                            // echo '<p>退選此課程是必修課<p>';
                        } else {
                            $sql = "DELETE  FROM select_list  where course_id = \"" . $code . "\" AND student_id = \"" . $studentID . "\" ;";
                            $result = mysqli_query($conn, $sql) or die('MySQL query error');
                            echo '退選成功';
                        }
                    }

                }

            }

        }

    }

}

?>