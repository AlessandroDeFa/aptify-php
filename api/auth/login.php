<?php
session_start();
include __DIR__ . "/../../lib/system.php";
include __DIR__ . "/../../lib/classes/DatabaseManager.php";
include __DIR__ . "/../../lib/classes/AuthManager.php";

$authManager = new AuthManager(new DatabaseManager());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        sendResponse(false, 'Username e password sono obbligatori.');
    }
    if ($authManager->login($username, $password)) {
        sendResponse(true, 'Accesso effettuato con successo!');
    }
    sendResponse(false, "Dati di accesso non corretti");
}
sendResponse(false, "Si è verificato un errore durante l'Accesso. Si prega di riprovare");
