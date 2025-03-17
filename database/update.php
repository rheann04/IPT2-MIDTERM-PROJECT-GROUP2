<?php
include('../database/database.php'); // Ensure correct path

session_start(); // Start session for status messages

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Debug: Check if data is received
    if (!isset($_POST["update_id"], $_POST["title"], $_POST["director"], $_POST["genre"], $_POST["release_date"])) {
        $_SESSION['status'] = "Missing required fields!";
        header("Location: ../index.php?error=Missing required fields");
        exit();
    }

    $id = $_POST["update_id"];
    $title = trim($_POST["title"]);
    $director = trim($_POST["director"]);
    $genre = trim($_POST["genre"]);
    $release_date = $_POST["release_date"];

    // Debug: Ensure ID is valid
    if (!is_numeric($id) || $id <= 0) {
        $_SESSION['status'] = "Invalid Movie ID!";
        header("Location: ../index.php?error=Invalid Movie ID");
        exit();
    }

    // Debug: Check for empty values
    if (empty($title) || empty($director) || empty($genre) || empty($release_date)) {
        $_SESSION['status'] = "All fields are required!";
        header("Location: ../index.php?error=All fields are required");
        exit();
    }

    // Prepare SQL statement
    $sql = "UPDATE movie SET title=?, director=?, genre=?, release_date=? WHERE id=?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssi", $title, $director, $genre, $release_date, $id);

        // Execute and check if successful
        if ($stmt->execute()) {
            $_SESSION['status'] = "Movie updated successfully!";
            header("Location: ../index.php?success=Movie updated successfully");
        } else {
            $_SESSION['status'] = "Error updating movie: " . $stmt->error;
            header("Location: ../index.php?error=" . urlencode($stmt->error));
        }
        $stmt->close();
    } else {
        $_SESSION['status'] = "Database error: " . $conn->error;
        header("Location: ../index.php?error=" . urlencode($conn->error));
    }
} else {
    $_SESSION['status'] = "Invalid request method!";
    header("Location: ../index.php?error=Invalid request");
}

$conn->close();
?>
