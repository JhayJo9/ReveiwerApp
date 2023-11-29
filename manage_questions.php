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

// Retrieve questions and answers for the user
// ... (existing code)

// Assuming $user_id is the ID of the logged-in user
$user_id = $_SESSION["user_id"];

// Retrieve questions and answers for the user
$sql = "SELECT q.id AS question_id, q.question, a.answer_text
        FROM questions q
        LEFT JOIN answers a ON q.id = a.question_id AND a.user_id = $user_id
        WHERE q.user_id = $user_id";

$result = $conn->query($sql);

// ... (existing code)

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Questions</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Manage Questions and Answers</h2>
    <p>Welcome, <?php echo $_SESSION["username"]; ?>!</p>
    <a href="logout.php">Logout</a>

    <h3>Your Questions and Answers:</h3>

    <table>
        <thead>
            <tr>
                <th>Question</th>
                <th>Answer</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['question']}</td>";
                echo "<td>{$row['answer_text']}</td>";
                echo "<td><a href='edit_question.php?id={$row['question_id']}'>Edit</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
