<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page
    header("Location: login_form.html");
    exit;
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    $question_id = $_POST["question_id"];
    $new_question = $conn->real_escape_string($_POST["question"]);
    $new_answer_text = $conn->real_escape_string($_POST["answer_text"]);

    // Update the question in the database
    $sql_update_question = "UPDATE questions SET question = '$new_question' WHERE id = $question_id AND user_id = $user_id";
    $sql_update_answer = "UPDATE answers SET answer_text = '$new_answer_text' WHERE question_id = $question_id AND user_id = $user_id";

    if ($conn->query($sql_update_question) === TRUE && $conn->query($sql_update_answer) === TRUE) {
        echo "Question updated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    // Redirect to manage_questions.php if the form was not submitted
    header("Location: manage_questions.php");
    exit;
}
?>
