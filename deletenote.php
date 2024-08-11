<?php

require "database.php";

session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: home.php");
    exit;
}

$noteId = $_GET['id'];

// Delete user-specific note
$statement = $conn->prepare("
    DELETE FROM notes 
    WHERE id = :id AND user_id = :user_id
");
$statement->execute([
    ':id' => $noteId,
    ':user_id' => $_SESSION['user']['id']
]);

$_SESSION["flash"] = ["message" => "Note deleted successfully."];

header("Location: home.php");
exit;
