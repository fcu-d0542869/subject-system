<?php
        $initCourse = array();
        $dbhost = '127.0.0.1';
        $dbuser = 'test';
        $dbpass = 'test1234';
        $dbname = 'course';
        $conn = mysqli_connect($dbhost, $dbuser, $dbpass) or die('Error with MySQL connection');

        mysqli_query($conn, "SET NAMES 'utf8'");
        mysqli_select_db($conn, $dbname);

        $sql = "DELETE FROM select_list;";
        $result = mysqli_query($conn, $sql) or die('MySQL query error');

        $sql = "SELECT student_id, course_id FROM student NATURAL JOIN course WHERE major = 'M'";
        $result = mysqli_query($conn, $sql) or die('MySQL query error');

        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {
                array_push($initCourse, [$row['student_id'], $row['course_id']]);
            }
        }
        for ($i = 0; $i < count($initCourse); $i++) {
            $tempStudentID = $initCourse[$i][0];
            $tempCourseID = $initCourse[$i][1];
            $sql = "INSERT INTO select_list VALUES ('$tempStudentID','$tempCourseID')";
            $result = mysqli_query($conn, $sql) or die('MySQL query error');
            $tempStudentID = null;
            $tempCourseID = null;
            echo '初始化';
        }

    

?>