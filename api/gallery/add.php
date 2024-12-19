<?php
session_start();
include __DIR__ . "/../../lib/system.php";
include __DIR__ . "/../../lib/classes/DatabaseManager.php";
include __DIR__ . "/../../lib/classes/AuthManager.php";

$databaseManager = new DatabaseManager();
$authManager = new AuthManager($databaseManager);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $media = $_POST['media'];
    $order = $_POST['order'];

    if(empty($media)){
        sendResponse(false, "Non hai inserito nessuna immagine.", 500);
    }

    if (!$authManager->isUserLoggedIn()){
        sendResponse(false, "Sessione scaduta. Accedi di nuovo", 401);
    }
    if ($databaseManager->addMediaToGallery($media, $order)){
        sendResponse(true, "Immagine caricata con successo!");
    }
    sendResponse(false, "Si è verificato un errore durante il caricamento dell' immagine. Si prega di riprovare", 500);
}
sendResponse(false, "Si è verificato un errore durante il caricamento dell' immagine. Si prega di riprovare", 500);
