<?php
session_start();
include __DIR__ . "/../../lib/system.php";
include __DIR__ . "/../../lib/classes/DatabaseManager.php";
include __DIR__ . "/../../lib/classes/AuthManager.php";

$authManager = new AuthManager(new DatabaseManager());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($authManager->logout()) {
        sendResponse(true, 'Logout effettuato con successo!');
    }
    sendResponse(false, "Si è verificato un errore durante il logout. Si prega di riprovare", 500);
}
sendResponse(false, "Si è verificato un errore durante il logout. Si prega di riprovare", 405);
