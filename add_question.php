<?php
// Start the session
session_start();

// Debug statement
//var_dump($_SESSION["user_id"]);

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page
    header("Location: login_form.html");
    exit;
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Process add question form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = (int)$_SESSION["user_id"];

    // Check if the values are set before using real_escape_string
    $question_text = isset($_POST["question_text"]) ? $conn->real_escape_string($_POST["question_text"]) : null;
    $answer = isset($_POST["answer"]) ? $conn->real_escape_string($_POST["answer"]) : null;

    // Check if the user ID exists in the users table
    $check_user_sql = "SELECT * FROM users WHERE id = $user_id";
    
    // Debug statement
    // echo "Debug: SQL query - $check_user_sql";

    $result_check_user = $conn->query($check_user_sql);

    // Debug statement
    // var_dump($result_check_user->num_rows);

    if ($result_check_user->num_rows === 0) {
        echo "Error: User not found";
        $conn->close();
        exit;
    }

    $result_check_user->close();

    // Insert question into the database using prepared statement
    $insert_question_sql = "INSERT INTO questions (user_id, question, answer) VALUES (?, ?, ?)";
    $stmt_insert_question = $conn->prepare($insert_question_sql);
    $stmt_insert_question->bind_param("iss", $user_id, $question_text, $answer);

    if ($stmt_insert_question->execute()) {
        echo "Question added successfully!";
    } else {
        echo "Error: " . $stmt_insert_question->error;
    }

    $stmt_insert_question->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Questions</title>
</head>
<body>
<a href="select_action_user.php">SELECT</a>
<h2>Add a Question</h2>

<form action="add_question.php" method="post">
    <label for="question_text">Question:</label>
    <input type="text" name="question_text" required><br>

    <label for="answer">Answer:</label>
    <input type="text" name="answer" required><br>

    <input type="submit" value="Add Question">
</form>

</body>
</html>
