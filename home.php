<?php

require "database.php";

session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

// Obtener las notas del usuario
$notes = $conn->prepare("
    SELECT * 
    FROM notes 
    WHERE user_id = :user_id
");
$notes->execute([':user_id' => $_SESSION['user']['id']]);

// Función para truncar el contenido a 25 caracteres
function truncateContent($content, $charLimit = 25) {
    $content = trim($content);
    
    // Recortar contenido si supera el límite de caracteres o espacios
    if (strlen($content) > $charLimit) {
        $truncatedContent = substr($content, 0, $charLimit);
        
        // Asegurarse de no cortar una palabra a la mitad
        $truncatedContent = preg_replace('/\s+?(\S+)?$/', '', $truncatedContent);
        
        // Añadir puntos suspensivos si se recortó el contenido
        return $truncatedContent . '...';
    }
    
    return $content;
}
?>
<?php require "partials/header.php" ?>

<div class="container" style="display: grid; justify-content: end;">
    <div class="row row-cols-1 row-cols-md-2 g-1 ">
        <?php if ($notes->rowCount() == 0): ?>
            <div class="col-12 text-center">
                <div class="card card-body mx-auto" style="max-width: 500px;">
                    <p>No notes saved yet</p>
                    <a href="addnote.php" class="btn btn-primary">Add One!</a>
                </div>
            </div>
        <?php endif ?>

        <?php foreach ($notes as $note): ?>
            <div class="col mb-2">
                <div class="card2">
                    <div class="card-body text-center" >
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
</div>

<?php require "partials/footer.php" ?>
