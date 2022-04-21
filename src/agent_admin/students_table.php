<?php

foreach ($all_students_info as $student_info) {
        echo "<tr>";

        echo "<th>";
        echo $student_info['name'];
        echo "</th>";

        echo "<th>";
        echo $student_info['email'];
        echo "</th>";

        echo "<th>";
        echo $student_info['phone'];
        echo "</th>";

        echo "<th>";
        echo $student_info['university'];
        echo "</th>";

        echo "<th>";
        echo $student_info['faculty'];
        echo "</th>";

        echo "<th>";
        echo $student_info['address'];
        echo "</th>";

        echo "<th>";
        echo $student_info['grad_year'];
        echo "</th>";

        echo "<th>";
        echo $student_info['agent'];
        echo "</th>";

        echo "</tr>";

    }; 
    echo "</table>";