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

// Get the question ID from the URL parameter
$question_id = $_GET['id'];

// Retrieve the question and answer for editing
$sql = "SELECT q.id AS question_id, q.question, a.answer_text
        FROM questions q
        LEFT JOIN answers a ON q.id = a.question_id AND a.user_id = $user_id
        WHERE q.user_id = $user_id AND q.id = $question_id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $question_id = $row['question_id'];
    $question = $row['question'];
    $answer_text = $row['answer_text'];
} else {
    // Redirect to manage_questions.php if the question is not found
    header("Location: manage_questions.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Question</title>
</head>
<body>
    <h2>Edit Question</h2>
    <p>Welcome, <?php echo $_SESSION["username"]; ?>!</p>
    <a href="logout.php">Logout</a>

    <form action="update_question.php" method="post">
        <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
        <label for="question">Question:</label>
        <input type="text" name="question" value="<?php echo $question; ?>" required>
        <br>
        <label for="answer_text">Answer:</label>
        <input type="text" name="answer_text" value="<?php echo $answer_text; ?>" required>
        <br>
        <input type="submit" value="Update Question">
    </form>

    <a href="manage_questions.php">Back to Manage Questions</a>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
