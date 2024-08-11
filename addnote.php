<?php

require "database.php";

session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["title"]) || empty($_POST["content"])) {
        $error = "Please fill all the fields.";
    } else {
        $title = $_POST["title"];
        $content = $_POST["content"]; // Keep content as plain text
        $createdAt = date('Y-m-d H:i:s'); // We capture the current date and time

        // Insert note
        $statement = $conn->prepare("INSERT INTO notes (user_id, title, content, created_at) VALUES (:user_id, :title, :content, :created_at)");
        $statement->execute([
            ":user_id" => $_SESSION["user"]["id"],
            ":title" => $title,
            ":content" => $content,
            ":created_at" => $createdAt,
        ]);

        // Reply with a success message (used by JavaScript)
        echo json_encode(["success" => true]);
        exit;
    }
}
?>

<?php require "partials/header.php" ?>

<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add New Note</div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <p class="text-danger"><?= htmlspecialchars($error) ?></p>
                    <?php endif ?>
                    <form id="note-form" method="POST">
                        <div class="mb-3 row">
                            <label for="title" class="col-md-4 col-form-label text-md-end">Title</label>
                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title" autocomplete="title" autofocus required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="content" class="col-md-4 col-form-label text-md-end">Content</label>
                            <div class="col-md-6">
                                <textarea id="content" class="form-control" name="content" rows="5" placeholder="Enter each item on a new line" required></textarea>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" id="submit-note" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('submit-note').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default form submission
    
    var form = document.getElementById('note-form');
    var formData = new FormData(form);

    fetch('addnote.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok. Status: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            window.location.href = 'home.php'; // Redirect to home.php if note added successfully
        } else {
            alert('An error occurred while adding the note.');
        }
    })
    .catch(error => {
        console.error('Error:', error); // Show exact error in console
        alert('An error occurred while sending the form.');
    });
});
</script>

<?php require "partials/footer.php" ?>
