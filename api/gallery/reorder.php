<?php
session_start();
include __DIR__ . "/../../lib/system.php";
include __DIR__ . "/../../lib/classes/DatabaseManager.php";
include __DIR__ . "/../../lib/classes/AuthManager.php";

$databaseManager = new DatabaseManager();
$authManager = new AuthManager($databaseManager);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_order = $_POST['newOrder'];

    if (!$authManager->isUserLoggedIn()){
        sendResponse(false, "Sessione scaduta. Accedi di nuovo", 401);
    }

    if(empty($new_order)){
        sendResponse(true, "Nuovo ordine salvato con successo!");
    }

    if ($databaseManager->reorderGallery($new_order)){
        sendResponse(true, "Nuovo ordine salvato con successo!");
    }
    sendResponse(false, "Si è verificato un errore. Si prega di riprovare", 500);
}
sendResponse(false, "Si è verificato un errore. Si prega di riprovare", 500);
