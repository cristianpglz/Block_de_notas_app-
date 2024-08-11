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

// Get the user's specific note
$note = $conn->prepare("
    SELECT * 
    FROM notes 
    WHERE id = :id AND user_id = :user_id
");
$note->execute([':id' => $noteId, ':user_id' => $_SESSION['user']['id']]);
$note = $note->fetch(PDO::FETCH_ASSOC);

if (!$note) {
    header("Location: home.php");
    exit;
}

$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["title"]) || empty($_POST["content"])) {
        $error = "Please fill all the fields.";
    } else {
        $title = $_POST["title"];
        $content = $_POST["content"];

        // Convert the content into a list, separating with new lines
        $contentList = array_filter(array_map('trim', explode("\n", $content)));
        $contentJson = json_encode($contentList);

        // Update note
        $statement = $conn->prepare("
            UPDATE notes 
            SET title = :title, content = :content
            WHERE id = :id AND user_id = :user_id
        ");
        $statement->execute([
            ":title" => $title,
            ":content" => $contentJson,
            ":id" => $noteId,
            ":user_id" => $_SESSION["user"]["id"]
        ]);

        $_SESSION["flash"] = ["message" => "Note updated successfully."];

        header("Location: home.php");
        exit;
    }
}
?>

<?php require "partials/header.php" ?>

<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Note</div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <p class="text-danger"><?= htmlspecialchars($error) ?></p>
                    <?php endif ?>
                    <form id="note-form" method="POST">
                        <div class="mb-3 row">
                            <label for="title" class="col-md-4 col-form-label text-md-end">Title</label>
                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title" value="<?= htmlspecialchars($note["title"]) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="content" class="col-md-4 col-form-label text-md-end">Content</label>
                            <div class="col-md-6">
                                <textarea id="content" class="form-control" name="content" rows="5" required><?= htmlspecialchars($note["content"]) ?></textarea>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require "partials/footer.php" ?>
