<?php
session_start();
include __DIR__ . "/../../lib/system.php";
include __DIR__ . "/../../lib/classes/DatabaseManager.php";
include __DIR__ . "/../../lib/classes/AuthManager.php";

$databaseManager = new DatabaseManager();
$authManager = new AuthManager($databaseManager);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];

    if (!$authManager->isUserLoggedIn()){
        sendResponse(false, "Sessione scaduta. Accedi di nuovo", 401);
    }
    if ($databaseManager->updateContent($content)){
        sendResponse(true, "Contenuto salvato con successo!");
    }
    sendResponse(false, "Si è verificato un errore durante il salvataggio del contenuto. Si prega di riprovare", 500);
}
sendResponse(false, "Si è verificato un errore durante il salvataggio del contenuto. Si prega di riprovare", 500);
