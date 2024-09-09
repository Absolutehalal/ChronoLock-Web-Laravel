<?php
$servername = "sql12.freesqldatabase.com";
$username = "sql12724238";
$password = "f8cI7wVnB5";
$dbname = "sql12724238";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_GET['what']) && $_GET['what'] == 'get_user') {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            $response = ["status" => "error", "message" => "Connection failed: " . $conn->connect_error];
            echo json_encode($response);
            exit();
        }

        $rfidcode = $conn->real_escape_string($_GET['RFID_Code']);

        // Direct query (note: this is vulnerable to SQL injection)
        $sql = "SELECT * FROM users JOIN rfid_accounts ON users.RFID_Code = rfid_accounts.RFID_Code WHERE users.RFID_Code = '$rfidcode'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response = ["status" => "success", "data" => $row];
        } else {
            $response = ["status" => "error"];
        }

        // Output the response in JSON format
        echo json_encode($response);

        // Close connection
        $conn->close();
    }

    if (isset($_GET['what']) && $_GET['what'] == 'get_faculty_schedule') {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            $response = ["status" => "error", "message" => "Connection failed: " . $conn->connect_error];
            echo json_encode($response);
            exit();
        }

        $current_day_number =  $_GET['current_day_number'];
        $current_time =  $_GET['current_time'];
        $current_date =  $_GET['current_date'];
        $rfidcode = $_GET['RFID_Code'];

        $sql = "SELECT * FROM users JOIN schedules ON 
      users.idNumber = schedules.userID JOIN class_lists ON 
      schedules.scheduleID = class_lists.scheduleID 
      WHERE scheduleStatus = 'With Class' AND day = '$current_day_number' AND '$current_time' BETWEEN startTime AND endTime AND 
      '$current_date' BETWEEN startDate AND endDate AND RFID_Code = '$rfidcode'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response = ["status" => "success", "data" => $row];
        } else {
            $response = ["status" => "error"];
        }
        // Output the response in JSON format
        echo json_encode($response);

        // Close connection
        $conn->close();
    }


    if (isset($_GET['what']) && $_GET['what'] == 'get_lab_status') {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            $response = ["status" => "error", "message" => "Connection failed: " . $conn->connect_error];
            echo json_encode($response);
            exit();
        }

        $current_day_number =  $_GET['current_day_number'];
        $current_time =  $_GET['current_time'];
        $current_date =  $_GET['current_date'];

        $sql = "SELECT attendances.classID FROM attendances JOIN class_lists ON 
        class_lists.classID = attendances.classID  JOIN schedules ON 
        class_lists.scheduleID = schedules.scheduleID
        WHERE date = '$current_date' AND day = '$current_day_number' AND '$current_time' BETWEEN startTime AND endTime AND 
        '$current_date' BETWEEN startDate AND endDate";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response = ["status" => "success", "data" => $row];
        } else {
            $response = ["status" => "error"];
        }
        // Output the response in JSON format
        echo json_encode($response);

        // Close connection
        $conn->close();
    }

    if (isset($_GET['what']) && $_GET['what'] == 'get_student_count') {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            $response = ["status" => "error", "message" => "Connection failed: " . $conn->connect_error];
            echo json_encode($response);
            exit();
        }

        $current_day_number =  $_GET['current_day_number'];
        $current_time =  $_GET['current_time'];
        $current_date =  $_GET['current_date'];

        $sql = "SELECT COUNT(attendances.userID) FROM attendances JOIN users ON 
        users.idNumber = attendances.userID JOIN class_lists ON 
        class_lists.classID = attendances.classID  JOIN schedules ON 
        class_lists.scheduleID = schedules.scheduleID
        WHERE userType = 'Student' AND day = '$current_day_number' AND '$current_time' BETWEEN startTime AND endTime AND 
        '$current_date' BETWEEN startDate AND endDate";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response = ["status" => "success", "data" => $row];
        } else {
            $response = ["status" => "error"];
        }
        // Output the response in JSON format
        echo json_encode($response);

        // Close connection
        $conn->close();
    }

    if (isset($_GET['what']) && $_GET['what'] == 'get_student_total_count') {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            $response = ["status" => "error", "message" => "Connection failed: " . $conn->connect_error];
            echo json_encode($response);
            exit();
        }

        $current_day_number =  $_GET['current_day_number'];
        $current_time =  $_GET['current_time'];
        $current_date =  $_GET['current_date'];

        $sql = "SELECT COUNT(student_masterlists.userID) FROM student_masterlists JOIN users ON 
        users.idNumber = student_masterlists.userID JOIN class_lists ON 
        class_lists.classID = student_masterlists.classID  JOIN schedules ON 
        class_lists.scheduleID = schedules.scheduleID
        WHERE userType = 'Student' AND day = '$current_day_number' AND '$current_time' BETWEEN startTime AND endTime AND 
        '$current_date' BETWEEN startDate AND endDate";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response = ["status" => "success", "data" => $row];
        } else {
            $response = ["status" => "error"];
        }
        // Output the response in JSON format
        echo json_encode($response);

        // Close connection
        $conn->close();
    }


    if (isset($_GET['what']) && $_GET['what'] == 'get_pending_rfid') {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            $response = ["status" => "error", "message" => "Connection failed: " . $conn->connect_error];
            echo json_encode($response);
            exit();
        }

        $rfidcode = $_GET['RFID_Code'];

        $sql = "SELECT RFID_Code FROM rfid_temps WHERE RFID_Code = '$rfidcode'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response = ["status" => "success", "data" => $row];
        } else {
            $response = ["status" => "error"];
        }

        // Output the response in JSON format
        echo json_encode($response);

        $conn->close();
    }

    if (isset($_GET['what']) && $_GET['what'] == 'log_rfid_entry') {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            $response = ["status" => "error", "message" => "Connection failed: " . $conn->connect_error];
            echo json_encode($response);
            exit();
        }
        $rfidcode = $_GET['RFID_Code'];

        $sql = "INSERT INTO rfid_temps (RFID_CODE) VALUES ('$rfidcode')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => 'RFID inserted successfully']);
        } else {
            echo json_encode(['error' => 'Error: ' . $conn->error]);
        }

        $conn->close();
    }

    if (isset($_GET['what']) && $_GET['what'] == 'store_attendance') {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            $response = ["status" => "error", "message" => "Connection failed: " . $conn->connect_error];
            echo json_encode($response);
            exit();
        }
        $user_ID = $_GET['user_ID'];
        $class_ID = $_GET['class_ID'];
        $current_date = $_GET['current_date'];
        $current_time = $_GET['current_time'];
        $remark = $_GET['remark'];

        $sql = "INSERT INTO attendances (userID,classID,date,time,remark) VALUES ('$user_ID','$class_ID','$current_date','$current_time','$remark')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => 'Attendance inserted successfully']);
        } else {
            echo json_encode(['error' => 'Error:']);
        }

        // Output the response in JSON format
        echo json_encode($response);

        $conn->close();
    }

    if (isset($_GET['what']) && $_GET['what'] == 'count_attendance') {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            $response = ["status" => "error", "message" => "Connection failed: " . $conn->connect_error];
            echo json_encode($response);
            exit();
        }
        $user_ID = $_GET['user_ID'];
        $class_ID = $_GET['class_ID'];
        $current_date = $_GET['current_date'];
        $current_time = $_GET['current_time'];
        $remark = $_GET['remark'];

        $sql = "INSERT INTO attendances (userID,classID,date,time,remark) VALUES ('$user_ID','$class_ID','$current_date','$current_time','$remark')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => 'Attendance inserted successfully']);
        } else {
            echo json_encode(['error' => 'Error:']);
        }
        $conn->close();
    }

    if (isset($_GET['what']) && $_GET['what'] == 'store_logs_deactivated_RFID_authorized_user') {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            $response = ["status" => "error", "message" => "Connection failed: " . $conn->connect_error];
            echo json_encode($response);
            exit();
        }

        $id = $_GET['id'];
        $action = $_GET['action'];
        $current_date = $_GET['current_date'];
        $current_time = $_GET['current_time'];

        $sql = "INSERT INTO user_logs (userID,action,date,time) VALUES ('$id','$action','$current_date','$current_time')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => 'Attendance inserted successfully']);
        } else {
            echo json_encode(['error' => 'Error: ' . $conn->error]);
        }
        $conn->close();
    }
    if (isset($_GET['what']) && $_GET['what'] == 'get_student_schedule') {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            $response = ["status" => "error", "message" => "Connection failed: " . $conn->connect_error];
            echo json_encode($response);
            exit();
        }

        $current_day_number =  $_GET['current_day_number'];
        $current_time =  $_GET['current_time'];
        $current_date =  $_GET['current_date'];
        $rfidcode = $_GET['RFID_Code'];

        $sql = " SELECT users.idNumber AS user_userID, userType, firstName, lastName, 
          student_masterlists.userID AS student_masterlist_userID, 
          student_masterlists.classID AS student_masterlist_classID, 
          class_lists.classID AS class_lists_classID, class_lists.scheduleID AS class_lists_scheduleID, 
          schedules.userID AS schedules_userID, schedules.scheduleID AS schedules_scheduleID, 
          schedules.startDate, schedules.endDate, schedules.startTime, schedules.endTime, schedules.day, 
          schedules.scheduleStatus FROM users 
          JOIN student_masterlists ON users.idNumber = student_masterlists.userID 
          JOIN class_lists ON student_masterlists.classID = class_lists.classID 
          JOIN schedules ON class_lists.scheduleID = schedules.scheduleID 
          WHERE day = '$current_day_number' AND '$current_time' BETWEEN startTime AND endTime AND '$current_date' BETWEEN startDate AND endDate AND RFID_Code = '$rfidcode'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response = ["status" => "success", "data" => $row];
        } else {
            $response = ["status" => "error"];
        }
        // Output the response in JSON format
        echo json_encode($response);

        // Close connection
        $conn->close();
    }

    if (isset($_GET['what']) && $_GET['what'] == 'check_instructor') {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            $response = ["status" => "error", "message" => "Connection failed: " . $conn->connect_error];
            echo json_encode($response);
            exit();
        }

        $instructor_ID =  $_GET['instructor_ID'];
        $class_id =  $_GET['class_id'];
        $student_day_number =  $_GET['student_day_number'];
        $student_startTime_check = $_GET['student_startTime_check'];
        $student_endTime_check = $_GET['student_endTime_check'];
        $student_schedule_start_date_check = $_GET['student_schedule_start_date_check'];
        $student_schedule_end_date_check = $_GET['student_schedule_end_date_check'];


        $sql = " SELECT * FROM attendances
          JOIN class_lists 
          ON attendances.classID = class_lists.classID 
          JOIN schedules ON class_lists.scheduleID = schedules.scheduleID  
          WHERE attendances.userID = '$instructor_ID' AND attendances.classID = '$class_id' AND
          day = '$student_day_number' AND time BETWEEN '$student_startTime_check' AND '$student_endTime_check' AND date BETWEEN '$student_schedule_start_date_check' AND '$student_schedule_end_date_check'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response = ["status" => "success", "data" => $row];
        } else {
            $response = ["status" => "error"];
        }
        // Output the response in JSON format
        echo json_encode($response);

        // Close connection
        $conn->close();
    }

    if (isset($_GET['what']) && $_GET['what'] == 'check_instructor_attendance') {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            $response = ["status" => "error", "message" => "Connection failed: " . $conn->connect_error];
            echo json_encode($response);
            exit();
        }

        $inst_ID =  $_GET['inst_ID'];
        $class_id =  $_GET['class_id'];
        $day =  $_GET['day'];
        $inst_startTime = $_GET['inst_startTime'];
        $inst_endTime = $_GET['inst_endTime'];
        $instschedule_startDate = $_GET['instschedule_startDate'];
        $inst_schedule_endDate = $_GET['inst_schedule_endDate'];


        $sql = " SELECT * FROM attendances 
          JOIN class_lists 
          ON attendances.classID = class_lists.classID 
          JOIN schedules ON class_lists.scheduleID = schedules.scheduleID  
          WHERE attendances.userID = '$inst_ID' AND attendances.classID = '$class_id' AND
          day = '$day' AND time BETWEEN '$inst_startTime' AND '$inst_endTime' AND date BETWEEN '$instschedule_startDate' AND '$inst_schedule_endDate'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response = ["status" => "success", "data" => $row];
        } else {
            $response = ["status" => "error"];
        }
        // Output the response in JSON format
        echo json_encode($response);

        // Close connection
        $conn->close();
    }

    if (isset($_GET['what']) && $_GET['what'] == 'check_student_attendance') {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            $response = ["status" => "error", "message" => "Connection failed: " . $conn->connect_error];
            echo json_encode($response);
            exit();
        }

        $student_ID =  $_GET['student_ID'];
        $class_id =  $_GET['class_id'];
        $day =  $_GET['day'];
        $inst_startTime = $_GET['inst_startTime'];
        $inst_endTime = $_GET['inst_endTime'];
        $instschedule_startDate = $_GET['instschedule_startDate'];
        $inst_schedule_endDate = $_GET['inst_schedule_endDate'];


        $sql = "SELECT * FROM attendances 
          JOIN class_lists 
          ON attendances.classID = class_lists.classID 
          JOIN schedules ON class_lists.scheduleID = schedules.scheduleID  
          WHERE attendances.userID = '$student_ID' AND attendances.classID = '$class_id' AND
          day = '$day' AND time BETWEEN '$inst_startTime' AND '$inst_endTime' AND date BETWEEN '$instschedule_startDate' AND '$inst_schedule_endDate'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response = ["status" => "success", "data" => $row];
        } else {
            $response = ["status" => "error"];
        }
        // Output the response in JSON format
        echo json_encode($response);

        // Close connection
        $conn->close();
    }

    if (isset($_GET['what']) && $_GET['what'] == 'check_and_mark_absence') {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $current_day_number = $_GET['current_day_number'];
        $current_time = $_GET['current_time'];
        $current_date = $_GET['current_date'];

        $sql = "SELECT schedules.scheduleID, schedules.userID, schedules.startTime, schedules.endTime, class_lists.classID AS class_lists_classID
            FROM schedules 
            JOIN users ON schedules.userID = users.idNumber
            JOIN class_lists ON schedules.scheduleID = class_lists.scheduleID
            WHERE userType = 'Faculty' AND scheduleStatus= 'With Class' AND day = ? AND endTime < ? AND startDate <= ? <= endDate";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("SQL prepare failed: " . $conn->error);
        }

        $stmt->bind_param('sss', $current_day_number, $current_time, $current_date);
        $stmt->execute();
        $result = $stmt->get_result();

        // Loop through the schedules
        while ($schedule = $result->fetch_assoc()) {
            // Check attendance for each schedule
            $attendance_sql = "SELECT * FROM attendances 
                               WHERE userID = ? 
                               AND classID = ? 
                               AND date = ?";
            $attendance_stmt = $conn->prepare($attendance_sql);
            if (!$attendance_stmt) {
                die("SQL prepare failed for attendance: " . $conn->error);
            }

            $attendance_stmt->bind_param('sss', $schedule['userID'], $schedule['class_lists_classID'], $current_date);
            $attendance_stmt->execute();
            $attendance_result = $attendance_stmt->get_result();
            $attendance_record = $attendance_result->fetch_assoc();

            // If no attendance record, mark as absent
            if (!$attendance_record) {
                $remark = "Absent";
                $time = null;

                // Call the function to store attendance
                $insert_sql = "INSERT INTO attendances (userID, classID, date, time, remark) VALUES (?, ?, ?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                if (!$insert_stmt) {
                    die("SQL prepare failed for insert: " . $conn->error);
                }

                $insert_stmt->bind_param('sssss', $schedule['userID'], $schedule['class_lists_classID'], $current_date, $time, $remark);
                if ($insert_stmt->execute()) {
                    echo json_encode([
                        'message' => "Marked user " . $schedule['userID'] . " as Absent for class " . $schedule['class_lists_classID'] . " on " . $current_date
                    ]);
                }

                $insert_stmt->close();
            }

            $attendance_stmt->close();
        }


        $stmt->close();
        $conn->close();
    }

    if (isset($_GET['what']) && $_GET['what'] == 'student_check_and_mark_absence') {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $current_day_number = $_GET['current_day_number'];
        $current_time = $_GET['current_time'];
        $current_date = $_GET['current_date'];

        $sql = "SELECT student_masterlists.userID, student_masterlists.classID AS student_masterlists_classID, startTime, endTime 
            FROM student_masterlists 
            JOIN users ON student_masterlists.userID = users.idNumber
            JOIN class_lists ON student_masterlists.classID = class_lists.classID 
            JOIN schedules ON class_lists.scheduleID = schedules.scheduleID
            WHERE userType = 'Student' AND scheduleStatus= 'With Class' AND day = ? AND endTime < ? AND startDate <= ? AND endDate >= ?";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param('ssss', $current_day_number, $current_time, $current_date, $current_date);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($schedule = $result->fetch_assoc()) {
            // Check attendance for each student
            $attendance_sql = "SELECT * FROM attendances 
                               WHERE userID = ? 
                               AND classID = ? 
                               AND date = ?";
            $attendance_stmt = $conn->prepare($attendance_sql);
            if (!$attendance_stmt) {
                die("Prepare failed: " . $conn->error);
            }

            $attendance_stmt->bind_param('sss', $schedule['userID'], $schedule['student_masterlists_classID'], $current_date);
            $attendance_stmt->execute();
            $attendance_result = $attendance_stmt->get_result();
            $attendance_record = $attendance_result->fetch_assoc();

            // If no attendance record, mark as absent
            if (!$attendance_record) {
                $remark = "Absent";
                $time = null;

                // Insert the attendance record
                $insert_sql = "INSERT INTO attendances (userID, classID, date, time, remark) VALUES (?, ?, ?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                if (!$insert_stmt) {
                    die("Prepare failed: " . $conn->error);
                }

                $insert_stmt->bind_param('sssss', $schedule['userID'], $schedule['student_masterlists_classID'], $current_date, $time, $remark);
                if ($insert_stmt->execute()) {
                    echo json_encode([
                        'message' => "Marked user " . $schedule['userID'] . " as Absent for class " . $schedule['student_masterlists_classID'] . " on " . $current_date
                    ]);
                }
                $insert_stmt->close();
            }
            $attendance_stmt->close();
        }
        $stmt->close();
        $conn->close();
    }
}