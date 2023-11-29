<?php
// Create a connection to the database

$localhost = "localhost";
$username = "root";
$password = "12345";
$mydatabase = "mydatabase";
$conn = new mysqli($localhost, $username, $password, $mydatabase);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the users table
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";

if ($conn->query($sql_users) === TRUE) {
    echo "Users table created successfully<br>";
} else {
    echo "Error creating users table: " . $conn->error . "<br>";
}

// Create the questions table
$sql_questions = "CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    answer VARCHAR(255) NOT NULL
)";

if ($conn->query($sql_questions) === TRUE) {
    echo "Questions table created successfully<br>";
} else {
    echo "Error creating questions table: " . $conn->error . "<br>";
}

// Close the database connection
$conn->close();
?>
