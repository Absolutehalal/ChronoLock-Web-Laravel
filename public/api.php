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
    $sql = "SELECT * FROM users WHERE RFID_Code = '$rfidcode'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response = ["status" => "success", "data" => $row];
    } else {
        $response = ["status" => "error", "message" => "No RFID code found"];
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
        $response = ["status" => "error", "message" => "No RFID code found"];
    }

    // Output the response in JSON format
    echo json_encode($response);
    $conn->close();
}
     
else if(isset($_GET['what']) && $_GET['what'] == 'log_rfid_entry'){
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
}



?>