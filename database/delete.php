<?php
include('database.php'); // Ensure database connection is included

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['ID']) && !empty($_POST['ID'])) {
        $id = intval($_POST['ID']); // Sanitize input

        // Prepare SQL query
        $sql = "DELETE FROM movie WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['status'] = "Movie deleted successfully!";
        } else {
            $_SESSION['status'] = "Failed to delete movie.";
        }

        $stmt->close();
    } else {
        $_SESSION['status'] = "Invalid movie ID.";
    }
}

// Redirect back to index.php
header("Location: ../index.php");
exit();
?>
