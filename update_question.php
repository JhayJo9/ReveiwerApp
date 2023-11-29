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

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Update the question in the database
        $sql_update_question = "UPDATE questions SET question = ? WHERE id = ? AND user_id = ?";
        $stmt_update_question = $conn->prepare($sql_update_question);
        $stmt_update_question->bind_param("sii", $new_question, $question_id, $user_id);
        $stmt_update_question->execute();

        // Update the answer in the database
        $sql_update_answer = "UPDATE answers SET answer_text = ? WHERE question_id = ? AND user_id = ?";
        $stmt_update_answer = $conn->prepare($sql_update_answer);
        $stmt_update_answer->bind_param("sii", $new_answer_text, $question_id, $user_id);
        $stmt_update_answer->execute();

        // Commit the transaction
        $conn->commit();
        
        echo "Question and Answer updated successfully!";
    } catch (Exception $e) {
        // Rollback the transaction on exception
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    // Close the prepared statements
    $stmt_update_question->close();
    $stmt_update_answer->close();

    // Close the database connection
    $conn->close();
} else {
    // Redirect to manage_questions.php if the form was not submitted
    header("Location: manage_questions.php");
    exit;
}
?>
