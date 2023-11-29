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

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user's answer and the question ID from the submitted form
    $question_id = $_POST["question_id"];
   // $user_answer = $_POST["user_answer"];

    // Retrieve the correct answer from the database
    $sql = "SELECT answer FROM questions WHERE id = $question_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $correct_answer = $row["answer"];

        // Display the correct answer
        echo "<p class='hello'>The correct answer is: $correct_answer</p><br>";
        exit; // Stop further execution to prevent duplicate output
    } else {
        echo "Question not found";
    }
}

// Retrieve a new question from the database
$sql = "SELECT * FROM questions ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $question = $row['question'];
    $questionId = $row['id'];

    // Display the new question
    echo "<p id='question'>Question: $question</p>";

    // Display the button to reveal the correct answer
    echo "<button id='revealAnswer'>Reveal Answer</button>";

    // Display the button to go to the next question
    echo "<button id='nextQuestion'>Next Question</button>";
} else {
    echo "<p>No questions found</p>";
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/answer.css">
</head>
<body>
<a href="select_action_user.php" id="selectLink">SELECT</a>


 <!-- Include jQuery library -->
 <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- JavaScript to reveal the correct answer and move to the next question -->
<script>
$(document).ready(function() {
    // Variable to track whether the buttons have been clicked
    var buttonsClicked = false;

    // Intercept button click for revealing the correct answer
    $('#revealAnswer').click(function(e) {
        // Prevent the default form submission behavior
        e.preventDefault();

        // Check if the buttons have been clicked
        if (!buttonsClicked) {
            buttonsClicked = true; // Set the flag to true to prevent multiple clicks

            // Submit the form via AJAX to reveal the correct answer
            $.ajax({
                type: 'POST',
                url: 'answer_question.php',
                data: { question_id: <?php echo $questionId; ?> },
                success: function(response) {
                    // Update the content with the correct answer
                    $('#question').html(response);
                }
            });
        }
    });

    // Intercept button click for moving to the next question
    $('#nextQuestion').click(function() {
        // Reload the page to get a new question
        location.reload();
    });
});

// Prevent the "SELECT" link from duplicating when AJAX is used
$('#selectLink').click(function(e) {
    e.preventDefault();
    location.href = 'select_action.php';
});
</script>

</body>
</html>

