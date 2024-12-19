<?php
session_start();
include __DIR__ . "/../../lib/system.php";
include __DIR__ . "/../../lib/classes/DatabaseManager.php";
include __DIR__ . "/../../lib/classes/AuthManager.php";

$authManager = new AuthManager(new DatabaseManager());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    if (empty($username) || empty($password)) {
        sendResponse(false, 'Username e password sono obbligatori.');
    }
    if ($password !== $confirmPassword){
        sendResponse(false, 'Le password non coincidono.');
    }
    if ($authManager->register($username, $password)) {
        sendResponse(true, 'Registrazione avvenuta con successo!');
    }
    sendResponse(false, 'Si è verificato un errore durante la registrazione. Si prega di riprovare');
}
sendResponse(false, 'Si è verificato un errore durante la registrazione. Si prega di riprovare');
