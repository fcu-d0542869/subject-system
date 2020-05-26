<a href = "index.php"> Go Query Interface</a> <p>
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
if (isset($_POST['MyHead'])) {
    $MyHead = $_POST["MyHead"];

    $dbhost = '127.0.0.1';
    $dbuser = 'test';
    $dbpass = 'test1234';
    $dbname = 'course';
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');
    mysqli_query($conn, "SET NAMES 'utf8'");
    mysqli_select_db($conn, $dbname);
    $sql = "SELECT * FROM course where course_id = \"" . $MyHead . "%\";";
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

}

?>
