<?php

require "database.php";

session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

// Get user notes
$notes = $conn->prepare("
    SELECT * 
    FROM notes 
    WHERE user_id = :user_id
");
$notes->execute([':user_id' => $_SESSION['user']['id']]);

// Function to truncate content to 25 characters
function truncateContent($content, $charLimit = 25) {
    $content = trim($content);
    
    // Trim content if it exceeds the character
    if (strlen($content) > $charLimit) {
        $truncatedContent = substr($content, 0, $charLimit);
        
        // Make sure you don't cut a word in half
        $truncatedContent = preg_replace('/\s+?(\S+)?$/', '', $truncatedContent);
        
        // Add ellipsis if content was cropped
        return $truncatedContent . '...';
    }
    
    return $content;
}
?>
<?php require "partials/header.php" ?>

<div class="container">
    <div class="row justify-content-center">
        <?php if ($notes->rowCount() == 0): ?>
            <div class="col-12 text-center">
                <div class="card card-body mx-auto" style="max-width: 500px;">
                    <p>No notes saved yet</p>
                    <a href="addnote.php" class="btn btn-primary">Add One!</a>
                </div>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 g-2">
                <?php foreach ($notes as $note): ?>
                    <div class="col mb-3">
                        <div class="card" style="max-width: 300px; margin: auto;">
                            <div class="card-body text-center">
                                <h3 class="card-title text-capitalize"><?= htmlspecialchars($note["title"]) ?></h3>
                                <p class="card-text"><?= nl2br(htmlspecialchars(truncateContent($note["content"]))) ?></p>
                                <p class="card-text text-muted"><?= htmlspecialchars($note["created_at"]) ?></p>
                                <a href="viewnotes.php?id=<?= $note["id"] ?>" class="btn btn-info mb-2">Read Full Note</a>
                                <a href="editnote.php?id=<?= $note["id"] ?>" class="btn btn-secondary mb-2">Edit Note</a>
                                <a href="deletenote.php?id=<?= $note["id"] ?>" class="btn btn-danger mb-2">Delete Note</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require "partials/footer.php" ?>
