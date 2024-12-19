<?php
session_start();
include __DIR__ . "/../lib/system.php";
include __DIR__ . "/../lib/classes/DatabaseManager.php";
include __DIR__ . "/../lib/classes/AuthManager.php";

$databaseManager = new DatabaseManager();
$authManager = new AuthManager($databaseManager);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tableId = $_POST['tableId'];
    $id = $_POST['id'];

    if (!$authManager->isUserLoggedIn()){
        sendResponse(false, "Sessione scaduta. Accedi di nuovo", 401);
    }
    if ($databaseManager->delete($tableId, $id)){
        sendResponse(true, "Elemento eliminato con successo!");
    }
    sendResponse(false, "Si è verificato un errore. Si prega di riprovare", 500);
}
sendResponse(false, "Si è verificato un errore. Si prega di riprovare", 500);
