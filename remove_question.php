<?php
// Connect to the database
$localhost = "localhost";
$username = "root";
$password = "12345";
$mydatabase = "mydatabase";
$conn = new mysqli($localhost, $username, $password, $mydatabase);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process remove question form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_id = $_POST["question_id"];

    $sql = "DELETE FROM questions WHERE id = $question_id";

    if ($conn->query($sql) === TRUE) {
        echo "Question removed successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
