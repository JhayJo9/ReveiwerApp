<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page
    header("Location: login_form.html");
    exit;
}

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

// Assuming $user_id is the ID of the logged-in user
$user_id = $_SESSION["user_id"];

// Display user's questions
$sql_questions = "SELECT * FROM questions WHERE user_id = $user_id";
$result_questions = $conn->query($sql_questions);

echo "<h2>User's Questions:</h2>";
while ($row = $result_questions->fetch_assoc()) {
    echo "<p>Question: " . $row['question'] . "</p>";
}

// Display user's answers
$sql_answers = "SELECT * FROM answers WHERE user_id = $user_id";
$result_answers = $conn->query($sql_answers);

echo "<h2>User's Answers:</h2>";
while ($row = $result_answers->fetch_assoc()) {
    echo "<p>Answer: " . $row['answer_text'] . "</p>";
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Action</title>
</head>
<body>
    <h2>Select Action</h2>
    <p>Welcome, <?php echo $_SESSION["username"]; ?>!</p>
    <a href="logout.php">Logout</a>
    <ul>
        <li><a href="add_question.php">Add Question</a></li>
        <li><a href="answer_question.php">Answer Question</a></li>
        <li><a href="manage_questions.php">View Question</a></li>
    </ul>
    
</body>
</html>