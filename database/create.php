<?php
session_start();
include('database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $title = $_POST['title'];
    $director = $_POST['director'];
    $genre = $_POST['genre'];
    $release_date = $_POST['release_date'];

    $sql = "INSERT INTO movie (title, director, genre, release_date) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $_POST['title'], $_POST['director'], $_POST['genre'], $_POST['release_date']);
$stmt->execute();

    if ($stmt->execute()){
        $_SESSION['status'] = "created";
    } else {
        $_SESSION['status'] = "error";
    }
    $stmt->close();
    $conn->close();
    header("Location: ../index.php");
    exit();
}
?>