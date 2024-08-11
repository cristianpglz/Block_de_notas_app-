<?php

require "database.php";

session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

// Get note ID from URL
$noteId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get the specific note
$note = $conn->prepare("
    SELECT * 
    FROM notes 
    WHERE user_id = :user_id AND id = :note_id
");
$note->execute([
    ':user_id' => $_SESSION['user']['id'],
    ':note_id' => $noteId
]);

// Get the note if it exists
$note = $note->fetch(PDO::FETCH_ASSOC);
if (!$note) {
    header("Location: home.php");
    exit;
}

?>

<?php require "partials/header.php" ?>

<div class="container pt-4 p-3">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header"><?= htmlspecialchars($note["title"]) ?></div>
                <div class="card-body">
                    <p class="m-2"><?= nl2br(htmlspecialchars($note["content"])) ?></p>
                    <p class="m-2">Date: <?= htmlspecialchars($note["created_at"]) ?></p>
                    <a href="home.php" class="btn btn-primary">Back to Home</a>
                    <a href="editnote.php?id=<?= $note["id"] ?>" class="btn btn-secondary mb-2">Edit Note</a>
                    <a href="deletenote.php?id=<?= $note["id"] ?>" class="btn btn-danger mb-2">Delete Note</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require "partials/footer.php" ?>
