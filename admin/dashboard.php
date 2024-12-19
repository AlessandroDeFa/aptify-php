<?php
session_start();
include __DIR__ . "/../config/application.php";
include __DIR__ . "/../lib/utils/utils.php";
include __DIR__ . "/../lib/classes/Routes.php";
include __DIR__ . "/../lib/classes/AuthManager.php";
include __DIR__ . "/../lib/classes/DatabaseManager.php";

$databaseManager = new DatabaseManager();
$authManager = new AuthManager($databaseManager);
if ($authManager->requiresRegistration()) {
    Routes::Registration();
}
if (!$authManager->isUserLoggedIn()){
    Routes::Login();
}

$page = isset($_GET['page']) ? $_GET['page'] : "contents";
$pageFile = __DIR__ . "/pages/$page.php";

if (!file_exists($pageFile)) {
    Routes::NotFound();
}
$fullUrl = getDomain();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $applicationIdentifier ?> • Pannello di Controllo</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
        <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="<?=$fullUrl?>/admin/css/styles.css">
    </head>
    <body>
        <div class="overlay">
        </div>
        <div class="confirmation-dialog">
            <div class="dialog-header">
                <h2></h2>
                <p></p>
            </div>
            <div class="dialog-actions">
                <button class="primary-button dismiss">Annulla</button>
                <button class="destructive-button submit">
                    <div>Elimina</div>
                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="animate-spin" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg>
                </button>
            </div>
        </div>
        <div class="dashboard container">
            <?php require_once __DIR__ . "/templates/header.php" ?>
            <main>
                <?php require_once __DIR__ . "/templates/sidebar.php" ?>
                <div class="container-content">
                    <?php require_once $pageFile ?>
                </div>
            </main>
        </div>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"
        ></script>
        <script src="https://unpkg.com/swapy/dist/swapy.min.js"></script>
        <?php if ($content != NULL){ ?>
        <script>
        const content = <?php echo json_encode($content) ?>;
        </script>
        <?php } ?>
        <script src="<?=$fullUrl?>/admin/js/classes/ContentDialog.js" type="module"></script>
        <script src="<?=$fullUrl?>/admin/js/classes/MediaManager.js" type="module"></script>
        <script src="<?=$fullUrl?>/admin/js/classes/GalleryReorderManager.js" type="module"></script>
        <script src="<?=$fullUrl?>/admin/js/classes/ConfirmationDialog.js" type="module"></script>
        <script src="<?=$fullUrl?>/admin/js/classes/AddMediaGalleryDialog.js" type="module"></script>
        <script src="<?=$fullUrl?>/admin/js/helpers.js"></script>
        <script src="<?=$fullUrl?>/admin/js/auth.js"></script>
        <script src="<?=$fullUrl?>/admin/js/main.js" type="module"></script>
    </body>
</html>
